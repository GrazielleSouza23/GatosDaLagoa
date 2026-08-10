<?php /** @var array $configs */ ?>
<section class="process-section" id="voluntariado">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo e($configs['titulo_voluntariado']['valor'] ?? ''); ?></h2>
            <p class="section-subtitle"><?php echo e($configs['descricao_voluntariado']['valor'] ?? ''); ?></p>
        </div><br>

        <div class="process-steps">
            <div class="process-step">
                <img src="/assets/images/icones/tampa.png" alt="Ícone da casinha" style="width:75px;height:75px;">
                <h3 class="process-title"><?php echo e($configs['titulo_casinha']['valor'] ?? ''); ?></h3>
                <p class="process-description"><?php echo e($configs['descricao_casinha']['valor'] ?? ''); ?></p>
            </div>

            <div class="process-step">
                <img src="/assets/images/icones/icons-cat-bowl.png" alt="Ícone de tigela de ração">
                <h3 class="process-title"><?php echo e($configs['titulo_alimentadores']['valor'] ?? ''); ?></h3>
                <p class="process-description"><?php echo e($configs['descricao_alimentadores']['valor'] ?? ''); ?></p>
            </div>

            <div class="process-step">
                <img src="/assets/images/icones/icons-volunteering.png" alt="Ícone de voluntariado">
                <h3 class="process-title"><?php echo e($configs['titulo_como_voluntariar']['valor'] ?? ''); ?></h3>
                <p class="process-description">
                    <?php echo e($configs['descricao_como_voluntariar']['valor'] ?? ''); ?>
                    <br><br>
                    <strong><?php echo e($configs['voluntariado_interesse']['valor'] ?? ''); ?></strong><br>
                    <a href="mailto:<?php echo e($configs['email_contato']['valor'] ?? ''); ?>"><?php echo e($configs['email_contato']['valor'] ?? ''); ?></a>
                </p>
            </div>
        </div>
    </div>
</section>
