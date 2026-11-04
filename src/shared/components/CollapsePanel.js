/**
 * Accordion panel, same markup and states as the bootstrap collapse panels
 * it replaces.
 */
import { useState } from '@wordpress/element';

export default function CollapsePanel( {
	id,
	title,
	defaultOpen = false,
	ariaExpanded = false,
	headingTag = 'h4',
	className = '',
	after = null,
	style,
	children,
} ) {
	const [ open, setOpen ] = useState( defaultOpen );
	// Bootstrap only updated aria-expanded once the panel was toggled.
	const [ touched, setTouched ] = useState( false );

	const toggle = () => {
		setOpen( ! open );
		setTouched( true );
	};

	const Heading = headingTag;
	let expanded = ariaExpanded ? 'true' : 'false';
	if ( touched ) {
		expanded = open ? 'true' : 'false';
	}

	return (
		<div className="panel panel-default" style={ style }>
			<div className="panel-heading">
				<Heading
					className={
						'panel-title' +
						( touched && ! open ? ' collapsed' : '' )
					}
					data-toggle="collapse"
					data-target={ '#' + id }
					aria-expanded={ expanded }
					onClick={ toggle }
				>
					{ title }
				</Heading>
			</div>
			<div
				id={ id }
				className={
					'panel-collapse collapse' +
					( open ? ' show' : '' ) +
					( className ? ' ' + className : '' )
				}
			>
				{ children }
			</div>
			{ after }
		</div>
	);
}
