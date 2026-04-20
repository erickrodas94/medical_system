<div class="space-y-4">
    <?php 
    $currentCategory = '';
    foreach($backgroundData as $bg): 
        if($currentCategory !== $bg['category']): 
            $currentCategory = $bg['category'];
    ?>
        <h4 class="text-xs font-bold text-slate-400 uppercase mt-6 mb-2"><?= __($currentCategory) ?></h4>
    <?php endif; ?>

    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
        <span class="text-sm text-slate-700 font-medium"><?= htmlspecialchars($bg['question_text']) ?></span>
        <div class="flex items-center gap-4">
            <?php if($bg['switch_value']): ?>
                <span class="px-2 py-1 bg-rose-100 text-rose-600 text-[10px] font-bold rounded">SÍ</span>
                <span class="text-xs text-slate-500 italic"><?= htmlspecialchars($bg['detail_text']) ?></span>
            <?php else: ?>
                <span class="px-2 py-1 bg-slate-200 text-slate-500 text-[10px] font-bold rounded">NO</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>