<?php
require_once '../includes/header.php';
?>


<section class="process-section" id="voluntariado">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo htmlspecialchars($configs["titulo_voluntariado"]["valor"] ?? ", "); ?></h2>
            <p class="section-subtitle"><?php echo htmlspecialchars($configs["descricao_voluntariado"]["valor"] ?? ", "); ?></p>
        </div><br>

        <div class="process-steps">
            <div class="process-step">
                   <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="75" height="75" viewBox="0,0,256,256">
                        <defs><linearGradient x1="6" y1="41" x2="42" y2="41" gradientUnits="userSpaceOnUse" id="color-1_wFfu6zXx15Yk_gr1"><stop offset="0" stop-color="#6ccb10"></stop><stop offset="1" stop-color="#6ccb10"></stop></linearGradient><linearGradient x1="14.095" y1="10.338" x2="31.385" y2="43.787" gradientUnits="userSpaceOnUse" id="color-2_wFfu6zXx15Yk_gr2"><stop offset="0" stop-color="#65c5b2"></stop><stop offset="0.495" stop-color="#65c5b2"></stop><stop offset="0.946" stop-color="#65c5b2"></stop><stop offset="1" stop-color="#65c5b2"></stop></linearGradient><linearGradient x1="24" y1="1.684" x2="24" y2="23.696" gradientUnits="userSpaceOnUse" id="color-3_wFfu6zXx15Yk_gr3"><stop offset="0" stop-color="#6ccb10"></stop><stop offset="1" stop-color="#6ccb10"></stop></linearGradient><linearGradient x1="28.05" y1="25.05" x2="35.614" y2="32.614" gradientUnits="userSpaceOnUse" id="color-4_wFfu6zXx15Yk_gr4"><stop offset="0" stop-color="#a8cf45"></stop><stop offset="1" stop-color="#6ccb10"></stop></linearGradient></defs><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.33333,5.33333)"><path d="M42,39h-36v2c0,1.105 0.895,2 2,2h32c1.105,0 2,-0.895 2,-2z" fill="url(#color-1_wFfu6zXx15Yk_gr1)"></path><path d="M42,39h-36v-19l18,-17l18,17z" fill="url(#color-2_wFfu6zXx15Yk_gr2)"></path><path d="M13,25h10c0.552,0 1,0.448 1,1v17h-12v-17c0,-0.552 0.448,-1 1,-1z" fill="#a8cf45"></path><path d="M24,4c-0.474,0 -0.948,0.168 -1.326,0.503l-5.359,4.811l-11.315,10.686v5.39l18,-15.962l18,15.962v-5.39l-11.315,-10.686l-5.359,-4.811c-0.378,-0.335 -0.852,-0.503 -1.326,-0.503z" fill="#000000" opacity="0.05"></path><path d="M24,3c-0.474,0 -0.948,0.167 -1.326,0.5l-5.359,4.784l-11.315,10.625v5.359l18,-15.871l18,15.871v-5.359l-11.315,-10.625l-5.359,-4.784c-0.378,-0.333 -0.852,-0.5 -1.326,-0.5z" fill="#000000" opacity="0.07"></path><path d="M44.495,19.507l-19.169,-17.004c-0.378,-0.335 -0.852,-0.503 -1.326,-0.503c-0.474,0 -0.948,0.168 -1.326,0.503l-19.169,17.004c-0.42,0.374 -0.449,1.02 -0.064,1.43l1.636,1.745c0.369,0.394 0.984,0.424 1.39,0.067l17.533,-15.321l17.533,15.322c0.405,0.356 1.021,0.327 1.39,-0.067l1.636,-1.745c0.385,-0.411 0.356,-1.057 -0.064,-1.431z" fill="url(#color-3_wFfu6zXx15Yk_gr3)"></path><path d="M29,25h6c0.552,0 1,0.448 1,1v6c0,0.552 -0.448,1 -1,1h-6c-0.552,0 -1,-0.448 -1,-1v-6c0,-0.552 0.448,-1 1,-1z" fill="url(#color-4_wFfu6zXx15Yk_gr4)"></path></g></g>
                    </svg>
                <h3 class="process-title"><?php echo htmlspecialchars($configs["titulo_casinha"]["valor"] ?? ", "); ?></h3>
                <p class="process-description">
                    <?php echo htmlspecialchars($configs["descricao_casinha"]["valor"] ?? ", "); ?>
                </p>
            </div>

            <div class="process-step">
                <img src="../assets/images/icones/icons-cat-bowl.png" alt="Ícone de tigela de ração">
                
                <h3 class="process-title"><?php echo htmlspecialchars($configs["titulo_alimentadores"]["valor"] ?? ", "); ?></h3>
                <p class="process-description">
                    <?php echo htmlspecialchars($configs["descricao_alimentadores"]["valor"] ?? ", "); ?>
                </p>
            </div>

            <div class="process-step">
                <img src="../assets/images/icones/icons-volunteering.png" alt="Ícone de voluntariado">

                <h3 class="process-title"><?php echo htmlspecialchars($configs["titulo_como_voluntariar"]["valor"] ?? ", "); ?></h3>
                <p class="process-description">
                    <?php echo htmlspecialchars($configs["descricao_como_voluntariar"]["valor"] ?? ''); ?>
                    <br><br>
                    <strong><?php echo htmlspecialchars($configs["voluntariado_interesse"]["valor"] ?? ''); ?></strong><br>
                   <a href="mailto:<?php echo htmlspecialchars($configs["email_contato"]["valor"] ?? ", "); ?>"><?php echo htmlspecialchars($configs["email_contato"]["valor"] ?? ", "); ?></a>
                </p>
            </div>
        </div>
    </div>
</section>


<?php
require_once '../includes/footer.php';
?>