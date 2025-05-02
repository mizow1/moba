/**
 * flex_ratio.js
 * 
 * wp-block-groupの子要素の幅比率とgapをクラス名から設定するJavaScript
 * - ratio_数字_数字_...の形式のクラス名を解析して、子要素のflex値を動的に設定する
 * - gap_数字の形式のクラス名を解析して、gap値を動的に設定する
 */

document.addEventListener('DOMContentLoaded', function() {
    // ratio_クラスの処理
    applyFlexRatios();
    
    // gap_クラスの処理
    applyGaps();
});

/**
 * ratio_クラスを持つ要素に対してflex値を設定する
 */
function applyFlexRatios() {
    // ratio_クラスを持つ要素をすべて取得
    const ratioGroups = document.querySelectorAll('.wp-block-group[class*="ratio_"]');
    
    // 各要素に対して処理を実行
    ratioGroups.forEach(function(group) {
        // クラス名からratio_クラスを抽出
        const ratioClass = Array.from(group.classList).find(className => 
            className.startsWith('ratio_') && /ratio_[0-9_]+/.test(className)
        );
        
        // ratio_クラスが見つからない場合は処理をスキップ
        if (!ratioClass) return;
        
        // ratio_クラスから数値を抽出
        const ratios = ratioClass.split('_')
            .slice(1) // "ratio_"を除去
            .map(Number); // 文字列を数値に変換
        
        // 数値が正しく抽出されなかった場合は処理をスキップ
        if (ratios.length === 0 || ratios.some(isNaN)) return;
        
        // 子要素を取得
        const children = Array.from(group.children);
        
        // 子要素の数が十分な場合のみ処理を続行
        if (children.length >= ratios.length) {
            // 各子要素に対してflex値を設定
            ratios.forEach((ratio, index) => {
                if (index < children.length) {
                    children[index].style.flex = ratio;
                }
            });
        }
    });
}

/**
 * gap_クラスを持つ要素に対してgap値を設定する
 */
function applyGaps() {
    // gap_クラスを持つ要素をすべて取得
    const gapGroups = document.querySelectorAll('.wp-block-group[class*="gap_"]');
    // 各要素に対して処理を実行
    gapGroups.forEach(function(group) {
        // クラス名からgap_クラスを抽出
        const gapClass = Array.from(group.classList).find(className => 
            className.startsWith('gap_') && /gap_[0-9]+/.test(className)
        );
        
        // gap_クラスが見つからない場合は処理をスキップ
        if (!gapClass) return;
        
        // gap_クラスから数値を抽出
        const gapValue = parseInt(gapClass.split('_')[1]);
        
        // 数値が正しく抽出されなかった場合は処理をスキップ
        if (isNaN(gapValue)) return;
        
        // gap値を設定
        group.style.gap = gapValue + '%';
    });
}
