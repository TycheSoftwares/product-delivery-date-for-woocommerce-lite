/**
 * Data printed by PRDD_Lite_Admin_React_Page::get_config(), or by
 * PRDD_Lite_Product_Metabox_React::enqueue_assets() on the product screen.
 */
const config = window.prddLiteAdmin || window.prddLiteProductMetabox || {};

export default config;

export const pluginUrl = config.plugin_url || '';
export const imageUrl = ( file ) => `${ pluginUrl }/assets/images/${ file }`;
