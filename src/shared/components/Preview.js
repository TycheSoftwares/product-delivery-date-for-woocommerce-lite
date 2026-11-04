/**
 * Markup of the static previews of the Pro screens shown under the Upgrade
 * to Pro modal. The previews have no state, and their fields have no names,
 * so nothing in them is submitted with the product form.
 */
import { __ } from '@wordpress/i18n';
import InfoTip from './InfoTip';

export function SaveButton() {
	return (
		<button type="button">
			{ __(
				'Save Settings',
				'product-delivery-date-for-woocommerce-lite'
			) }
		</button>
	);
}

/**
 * Page title, description and Save Settings button.
 *
 * @param {Object} props
 * @param {string} props.title     Title.
 * @param {string} props.text      Description.
 * @param {string} props.className Class of the header.
 * @param {string} props.textClass Class of the title column.
 */
export function PageHead( {
	title,
	text,
	className = 'prdd-page-head phw-btn',
	textClass = 'float-left prdd-container',
} ) {
	return (
		<div className={ className }>
			<div className={ textClass }>
				<h1>{ title }</h1>
				<p>{ text }</p>
			</div>
			<div className="float-right">
				<SaveButton />
			</div>
		</div>
	);
}

/**
 * Open accordion panel.
 *
 * @param {Object} props
 * @param {string} props.id         Id of the panel body, styled by the Pro CSS.
 * @param {string} props.title      Title.
 * @param {string} props.headingTag Heading element.
 * @param {*}      props.children   Rows.
 */
export function Panel( { id, title, headingTag = 'h4', children } ) {
	const Heading = headingTag;
	return (
		<div className="panel panel-default">
			<div className="panel-heading">
				<Heading className="panel-title" aria-expanded="false">
					{ title }
				</Heading>
			</div>
			<div id={ id } className="panel-collapse collapse show">
				<div className="panel-body">
					<div className="tbl-mod-1">{ children }</div>
				</div>
			</div>
		</div>
	);
}

/**
 * Accordion of the settings pages.
 *
 * @param {Object} props
 * @param {*}      props.children Panels.
 */
export function Accordion( { children } ) {
	return (
		<div className="wbc-accordion">
			<div className="panel-group prdd-accordian">{ children }</div>
		</div>
	);
}

/**
 * Label and field row with the info icon.
 *
 * @param {Object} props
 * @param {string} props.label     Label.
 * @param {string} props.className Class of the row.
 * @param {string} props.wrapClass Class of the field wrapper.
 * @param {*}      props.children  Field.
 */
export function Row( {
	label,
	className = 'tm1-row',
	wrapClass = 'rc-flx-wrap flx-aln-center',
	children,
} ) {
	return (
		<div className={ className }>
			<div className="col-left">
				<label>{ label }</label>
			</div>
			<div className="col-right">
				<div className={ wrapClass }>
					<InfoTip />
					{ children }
				</div>
			</div>
		</div>
	);
}

export function Switch() {
	return (
		<label className="el-switch el-switch-green">
			<input type="checkbox" />
			<span className="el-switch-style"></span>
		</label>
	);
}

export function Radio( { label, checked = false } ) {
	return (
		<div className="rb-flx-style">
			<div className="el-radio el-radio-green">
				<input type="radio" defaultChecked={ checked } />
				<label className="el-radio-style"></label>
			</div>
			<label>{ label }</label>
		</div>
	);
}

export function Checkbox( { label, checked = false, className = '' } ) {
	return (
		<div className={ `custom-control custom-checkbox ${ className }` }>
			<input
				type="checkbox"
				className="custom-control-input"
				defaultChecked={ checked }
			/>
			<label className="custom-control-label">{ label }</label>
		</div>
	);
}
