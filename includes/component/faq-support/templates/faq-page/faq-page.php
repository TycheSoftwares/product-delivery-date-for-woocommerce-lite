<?php
/**
 * FAQ & Support page
 */
?>
<style>
    .faq-ts-accordion {
        background-color: #ccc;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
        margin-bottom: 5px;
    }
    .active, .faq-ts-accordion:hover {
        background-color: #ccc; 
    }
    .faq-ts-accordion:after {
        content: '\002B';
        color: #777;
        font-weight: bold;
        float: right;
        margin-left: 5px;
    }
    .active:after {
        content: "\2212";
    }
    .panel {
        padding: 0 18px;
        display: none;
        background-color: light-grey;
        overflow: hidden;
    }
    .main-panel {
        width: 650px !important;
    }
    .support-panel {
        padding: 5px;
    }
    .dashicons-external {
        content: "\f504";
    }
    .dashicons-editor-help {
        content: "\f223";
    }
    div.panel.show {
        display: block !important;
    }

</style>

<div class="main-panel">
    <h3>
        <?php 
        printf(
            esc_html__( 'Frequently Asked Questions for %s Plugin', 'woocommerce-prdd-lite' ),
            esc_html__( $ts_plugin_name, 'woocommerce-prdd-lite' )
        );
        ?>
    </h3>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[1]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[1]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[2]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[2]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[3]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[3]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[4]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[4]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[5]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[5]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[6]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[6]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[7]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[7]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[8]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[8]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[9]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[9]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>
    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong><?php echo __( $ts_faq[10]['question'], 'woocommerce-prdd-lite' ); ?></strong></button>
    <div class="panel">
        <p><?php echo __( $ts_faq[10]['answer'], 'woocommerce-prdd-lite' ); ?></p>
    </div>
</div>

<div class="support-panel">
    <p style="font-size: 19px">
    <?php 
    echo sprintf(
        esc_html__( 'If your queries are not answered here, you can send an email directly to %s for some additional requirements.', 'woocommerce-prdd-lite' ),
        '<strong>' . esc_html__( 'support@tychesoftwares.com', 'woocommerce-prdd-lite' ) . '</strong>'
    );
    ?>
</p>

</div>
<script>
var acc = document.getElementsByClassName("faq-ts-accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function() {
        hideAll();

        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
    }
}

function hideAll() {
    for (i = 0; i < acc.length; i++) {
        acc[i].classList.toggle( "active", false);
        acc[i].nextElementSibling.classList.toggle( "show", false );
    }
}

</script>