/**
 * WordPressギャラリーブロックカスタマイズ
 * 横スクロールでループするギャラリーを実装
 */
(function($) {
    'use strict';

    // ギャラリークラス
    class ScrollGallery {
        constructor(element) {
            this.gallery = element;
            this.isScrolling = true;
            this.scrollSpeed = 1; // スクロールスピード
            this.direction = 1; // 1: 左へスクロール, -1: 右へスクロール
            this.scrollInterval = null;
            this.touchStartX = 0;
            this.touchEndX = 0;
            this.modalOpen = false;
            this.scrollPosition = 0; // スクロール位置を追跡
            
            // 初期化
            this.init();
        }

        init() {
            
            // 画像要素を検索
            this.images = this.gallery.find('img');
            
            if (this.images.length === 0) {
                return; // 初期化を中止
            }
            
            
            // ビューポート幅を取得
            const viewportWidth = $(window).width();
            // 画像1枚あたりの幅を計算（ビューポート幅の20%）
            const imageWidth = Math.floor(viewportWidth * 0.2);
            // スマホ判定（ビューポート幅が768px未満）
            const isMobile = viewportWidth < 768;
            // SP版とPC版で幅を変える
            const itemWidth = isMobile ? '40vw' : '20vw';
            // ギャラリーの高さを設定（PCでは200px固定、SPではアイテム幅と同じ）
            const galleryHeight = isMobile ? itemWidth : '200px';
            
            
            // ギャラリーコンテナのスタイル設定
            this.gallery.css({
                'overflow': 'hidden',
                'position': 'relative',
                'width': '100cqw', // ウインドウ幅全体でギャラリー（100vwでは横スクロールバーが出るため）
                'height': galleryHeight, // PCでは200px固定、SPではアイテム幅と同じ
                'margin-left': 'calc(50% - 50vw)', // 中央揃えのためのマージン調整
                'margin-right': 'calc(50% - 50vw)',
                'margin-top': '2em',
                'margin-bottom': '2em',
                'box-sizing': 'border-box' // ブラウザ横スクロールバー防止
            });
            
            // ギャラリーの内容をクリア
            this.gallery.empty();
            
            // スクロール用のラッパーを作成
            this.wrapper = $('<div class="gallery-scroll-wrapper"></div>');
            this.wrapper.css({
                'display': 'flex',
                'flex-wrap': 'nowrap',
                'position': 'relative',
                'width': 'max-content',
                'height': galleryHeight, // PCでは200px固定、SPではアイテム幅と同じ
                'padding': '0 calc(50vw - 50%)'  // 左右に余白を追加
            });
            
            // 元のコンテンツからアイテムを作成
            this.items = [];
            this.images.each((index, img) => {
                const $img = $(img);
                const item = $('<div class="gallery-item"></div>');
                item.css({
                    'flex': '0 0 auto',
                    'width': itemWidth, // SP版は40vw、PC版は20vw
                    'height': galleryHeight, // SP版は幅と同じ（40vw）、PCでは200px固定
                    'overflow': 'hidden', // オーバーフローを隠す
                    'position': 'relative', // 子要素の位置指定のため
                    'cursor': 'pointer'
                });
                
                // 新しい画像要素を作成
                const newImg = $('<img>');
                newImg.attr('src', $img.attr('src'));
                newImg.attr('alt', $img.attr('alt') || '');
                newImg.css({
                    'position': 'absolute', // 絶対位置指定
                    'top': '0', // 上端を起点
                    'left': '50%', // 左右中央を起点
                    'transform': 'translateX(-50%)', // 中央揃え
                    'width': 'auto', // 幅は自動
                    'height': '100%', // 高さは100%
                    'min-width': '100%', // 最小幅は100%
                    'object-fit': 'cover', // 画像をカバーするようにトリミング
                    'object-position': 'center top' // 上端中央を基準
                });
                
                // キャプションがあれば追加
                const caption = $img.closest('figure').find('figcaption').text();
                if (caption) {
                    const captionElement = $('<div class="gallery-caption"></div>');
                    captionElement.text(caption);
                    captionElement.css({
                        'position': 'absolute',
                        'bottom': '0',
                        'left': '0',
                        'right': '0',
                        'padding': '8px',
                        'font-size': '0.85em',
                        'line-height': '1.4',
                        'text-align': 'center',
                        'background': 'rgba(0, 0, 0, 0.5)',
                        'color': 'white'
                    });
                    item.append(newImg).append(captionElement);
                } else {
                    item.append(newImg);
                }
                
                this.items.push(item);
                this.wrapper.append(item);
            });
            
            // 複製してループ効果を作成（十分な数の要素を追加）
            const originalItems = this.items.slice();
            for (let i = 0; i < 2; i++) {
                originalItems.forEach(item => {
                    const clonedItem = item.clone(true);
                    this.wrapper.append(clonedItem);
                    this.items.push(clonedItem);
                });
            }
            
            // ラッパーをギャラリーに追加
            this.gallery.append(this.wrapper);
            
            // アイテムの合計幅を計算
            this.totalWidth = 0;
            this.items.forEach(item => {
                this.totalWidth += item.outerWidth(true);
            });
            
            
            // スクロールアニメーション開始
            this.startScroll();
            
            // イベントリスナーの設定
            this.setupEventListeners();
            
            // モーダル要素の作成
            this.createModal();
            
            // 画像の読み込みを待ってからギャラリーを表示
            this.loadImages();
        }
        
        // 画像の読み込みを待つ
        loadImages() {
            let loadedImages = 0;
            const totalImages = this.items.length;
            const self = this;
            
            // 各画像の読み込みを監視
            this.items.forEach(item => {
                const img = item.find('img')[0];
                
                // 既に読み込み済みの場合
                if (img.complete) {
                    loadedImages++;
                    // すべての画像が読み込まれたらギャラリーを表示
                    if (loadedImages === totalImages) {
                        self.showGallery();
                    }
                } else {
                    // 画像の読み込みイベントを監視
                    $(img).on('load', function() {
                        loadedImages++;
                        // すべての画像が読み込まれたらギャラリーを表示
                        if (loadedImages === totalImages) {
                            self.showGallery();
                        }
                    });
                    
                    // 画像の読み込みエラー時も次に進む
                    $(img).on('error', function() {
                        loadedImages++;
                        // すべての画像が読み込まれたらギャラリーを表示
                        if (loadedImages === totalImages) {
                            self.showGallery();
                        }
                    });
                }
            });
            
            // タイムアウト（5秒経過してもロードが完了しない場合は強制表示）
            setTimeout(() => {
                if (!this.gallery.hasClass('gallery-loaded')) {
                    this.showGallery();
                }
            }, 5000);
        }
        
        // ギャラリーを表示
        showGallery() {
            this.gallery.addClass('gallery-loaded');
        }

        startScroll() {
            if (this.scrollInterval) {
                clearInterval(this.scrollInterval);
            }
            
            const originalItemsWidth = this.totalWidth / 3;
            const firstSetWidth = originalItemsWidth;
            
            this.scrollInterval = setInterval(() => {
                if (this.isScrolling) {
                    // スクロール位置を更新
                    this.scrollPosition += this.scrollSpeed * this.direction;
                    
                    // 無限スクロールの実装
                    if (this.direction > 0) { // 左方向へのスクロール
                        if (this.scrollPosition >= firstSetWidth) {
                            // 最初のセットを超えたら、最後に移動して見た目上連続するように
                            this.scrollPosition -= firstSetWidth;
                            this.wrapper.css('transform', `translateX(${-this.scrollPosition}px)`);
                        } else {
                            // 通常のスクロール
                            this.wrapper.css('transform', `translateX(${-this.scrollPosition}px)`);
                        }
                    } else { // 右方向へのスクロール
                        if (this.scrollPosition < 0) {
                            // 最初より前にスクロールしたら、最後のセットの終わりに移動
                            this.scrollPosition += firstSetWidth;
                            this.wrapper.css('transform', `translateX(${-this.scrollPosition}px)`);
                        } else {
                            // 通常のスクロール
                            this.wrapper.css('transform', `translateX(${-this.scrollPosition}px)`);
                        }
                    }
                    
                    // 5秒ごとにデバッグメッセージを表示
                    const now = new Date().getTime();
                    if (!this.lastDebugTime || now - this.lastDebugTime > 5000) {
                        this.lastDebugTime = now;
                    }
                }
            }, 20);
        }

        stopScroll() {
            this.isScrolling = false;
        }

        resumeScroll() {
            if (!this.modalOpen) {
                this.isScrolling = true;
            }
        }

        setupEventListeners() {
            // アイテムのイベントリスナー
            this.items.forEach(item => {
                // ホバー時にスクロールを停止
                item.on('mouseenter', () => {
                    this.stopScroll();
                });
                
                // ホバーが外れたらスクロールを再開
                item.on('mouseleave', () => {
                    this.resumeScroll();
                });
                
                // クリック時にモーダル表示
                item.on('click', (e) => {
                    const imgElement = $(e.currentTarget).find('img');
                    if (imgElement.length > 0) {
                        const imgSrc = imgElement.attr('src');
                        const imgAlt = imgElement.attr('alt') || '';
                        this.showModal(imgSrc, imgAlt);
                    }
                });
            });
            
            // タッチイベント（スワイプ対応）
            this.gallery.on('touchstart', (e) => {
                this.stopScroll();
                this.touchStartX = e.originalEvent.touches[0].clientX;
            });
            
            this.gallery.on('touchmove', (e) => {
                if (!this.isScrolling) {
                    const touchX = e.originalEvent.touches[0].clientX;
                    const diff = this.touchStartX - touchX;
                    this.scrollPosition += diff;
                    this.wrapper.css('transform', `translateX(${-this.scrollPosition}px)`);
                    this.touchStartX = touchX;
                }
            });
            
            this.gallery.on('touchend', () => {
                this.resumeScroll();
            });
            
            // ウィンドウリサイズ時にギャラリーの高さを更新
            $(window).on('resize', () => {
                const newWidth = $(window).width();
                const isMobile = newWidth < 768;
                const newItemWidth = isMobile ? '40vw' : '20vw';
                const newHeight = isMobile ? newItemWidth : '200px';
                
                this.gallery.css('height', newHeight);
                this.wrapper.css('height', newHeight);
                this.items.forEach(item => {
                    item.css({
                        'width': newItemWidth,
                        'height': newHeight
                    });
                });
            });
        }

        createModal() {
            // モーダル要素がまだ存在しない場合のみ作成
            if ($('#gallery-modal').length === 0) {
                const modal = $('<div id="gallery-modal" class="gallery-modal"></div>');
                const modalContent = $('<div class="gallery-modal-content"></div>');
                const closeBtn = $('<span class="gallery-modal-close">&times;</span>');
                const modalImg = $('<img class="gallery-modal-img">');
                const modalCaption = $('<div class="gallery-modal-caption"></div>');
                
                modalContent.append(closeBtn);
                modalContent.append(modalImg);
                modalContent.append(modalCaption);
                modal.append(modalContent);
                
                $('body').append(modal);
                
                // モーダルを閉じる
                closeBtn.on('click', () => {
                    this.hideModal();
                });
                
                // モーダル外をクリックしても閉じる
                modal.on('click', (e) => {
                    if ($(e.target).is(modal)) {
                        this.hideModal();
                    }
                });
                
                // ESCキーでも閉じる
                $(document).on('keydown', (e) => {
                    if (e.key === 'Escape' && this.modalOpen) {
                        this.hideModal();
                    }
                });
            }
        }

        showModal(imgSrc, imgAlt) {
            const modal = $('#gallery-modal');
            const modalImg = modal.find('.gallery-modal-img');
            const modalCaption = modal.find('.gallery-modal-caption');
            
            modalImg.attr('src', imgSrc);
            modalCaption.text(imgAlt);
            
            modal.css('display', 'flex');
            this.modalOpen = true;
            this.stopScroll();
            
            // 画像の読み込みが完了したらフェードイン
            modalImg.on('load', function() {
                $(this).addClass('loaded');
            });
        }

        hideModal() {
            const modal = $('#gallery-modal');
            modal.css('display', 'none');
            modal.find('.gallery-modal-img').removeClass('loaded');
            this.modalOpen = false;
            this.resumeScroll();
        }
    }

    // DOMの準備ができたら実行
    $(document).ready(function() {
        
        // 既存のギャラリーにもロード完了クラスを追加（ギャラリーではない画像要素のため）
        $('.wp-block-image:has(img:only-child)').addClass('gallery-loaded');
        
        // 単一の画像要素はスキップ
        $('.wp-block-image').each(function() {
            const $this = $(this);
            // 複数の画像がある場合のみギャラリーとして扱う
            if ($this.find('img').length > 1) {
                if (!$this.data('scroll-gallery-initialized')) {
                    $this.data('scroll-gallery-initialized', true);
                    new ScrollGallery($this);
                }
            }
        });
        
        // ギャラリーブロックを検索して初期化
        $('.wp-block-gallery, .blocks-gallery-grid, .gallery').each(function() {
            if (!$(this).data('scroll-gallery-initialized')) {
                $(this).data('scroll-gallery-initialized', true);
                new ScrollGallery($(this));
            }
        });
    });

})(jQuery);
