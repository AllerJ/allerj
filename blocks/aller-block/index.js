// Hack for adding background colors to Bootstrap Columns
function myColumnBgColorOptions( bgColorOptions ) {
	bgColorOptions = CUSTOMIZER.brandColors;
	return bgColorOptions;
}
wp.hooks.addFilter(
	'wpBootstrapBlocks.column.bgColorOptions',
	'allerj/wp-bootstrap-blocks/column/bgColorOptions',
	myColumnBgColorOptions
);

// Hack for adding Padding classes to Columns
function myColumnPaddingOptions( paddingOptions ) {
	paddingOptions.push( { label: 'Semi Large', value: 'p-4' } );
	paddingOptions.push( { label: 'Remove All', value: 'p-0' } );
	return paddingOptions;
}
wp.hooks.addFilter(
	'wpBootstrapBlocks.column.paddingOptions',
	'allerj/wp-bootstrap-blocks/column/paddingOptions',
	myColumnPaddingOptions
);

// Hack for adding Button Styles
function myButtonStyleOptions( styleOptions ) {
	styleOptions.push( { label: 'Brand Button', value: 'brand' } );
	styleOptions.push( { label: 'Brand Button Small', value: 'brand btn-sm' } );
	styleOptions.push( { label: 'Brand Button Large', value: 'brand btn-lg' } );
	styleOptions.push( { label: 'Brand Button Small Square', value: 'brand btn-sm btn-square' } );
	styleOptions.push( { label: 'Brand Button Large Square', value: 'brand btn-sm btn-square' } );

	return styleOptions;
}
wp.hooks.addFilter(
	'wpBootstrapBlocks.button.styleOptions',
	'allerj/wp-bootstrap-blocks/button/styleOptions',
	myButtonStyleOptions
);


// Start of DashBlock
( function(blocks, editor, element, components) {
	const __ = wp.i18n.__; // The __() for internationalization.
	const el = element.createElement; 
	const registerBlockType = blocks.registerBlockType; 
	const {
		BlockControls, 
		AlignmentToolbar, 
		PanelColorSettings,
		getColorClassName,
		getColorObjectByColorValue,
		InspectorControls,
		RichText
	} = wp.blockEditor;
		
	const { Fragment } = element;
	
	const {
		TextControl,
		CheckboxControl,
		RadioControl,
		SelectControl,
		TextareaControl,
		ToggleControl,
		RangeControl,
		Panel,
		PanelBody,
		PanelRow
	} = wp.components;


	function addListBlockClassName( settings, name ) {
		if ( name !== 'allerj/aller-block' ) {
			return settings;
		}
	 
		return lodash.assign( {}, settings, {
			supports: lodash.assign( {}, settings.supports, {
				className: true
			} ),
		} );
	}
	 
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'allerj/blocks/aller-block',
		addListBlockClassName
	);

	
	registerBlockType( 'allerj/aller-block', { 
		title: __( 'Aller Block', 'allerj' ), 
		transforms: {
			from: [
				{
					type: 'block',
					blocks: [ 'core/paragraph' ],
					transform: ( { content } ) => {
						return wp.blocks.createBlock( 'allerj/aller-block', {
							content,
						} );
					},
				},
			]
		},
		icon: 'palmtree', 
		category: 'common',
		keywords: [ 'css', 'dash', 'header', 'advanced', 'heading' ], // keywords for custom block
		attributes: {
			content: {
				type: 'string',
				default: ''
			},
			alignment: {
				type: 'string',
				default: '',
			},
			textColor: { 
				type: 'string',
				default: "",
			},
			textColorClass: {
				type: 'string',
				default: ""
			},
			elementType: {
				type: 'string',
				default: 'p'
			},
			className: {
				type: 'string',
				default: ''
			},
			font: {
				type: 'string',
				default: ''
			},
			fontSize: {
				type: 'string',
				default: ''
			},
			fontWeight: {
				type: 'string',
				default: ''
			},
			textTransform: {
				type: 'string',
				default: ''
			},
			lineHeight: {
				type: 'string',
				default: ''
			},
			letterSpacing: {
				type: 'string',
				default: ''
			},
			extraCSS: {
				type: 'string',
				default: ''
			}
		}, //placeholders to store customized settings information about the block
		example: { // used to define default values for the attributes
			attributes: {
				alignment: '',
				elementType: 'p',
				className: ''
			},
		},
		edit: (function( props ) {
			const onChangeAlignment = ( newAlignment ) => {
				props.setAttributes( { alignment: newAlignment === undefined ? '' : newAlignment } );
			};
			
			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}
			
			const colorBrands = CUSTOMIZER.brandColors;
			const brandFontOptions = CUSTOMIZER.googleFonts;

			const textTransformOptions = [
				{ label: 'Default', value: '' },
				{ label: 'UPPERCASE', value: 'uppercase' },
				{ label: 'lowercase', value: 'lowercase' }
			];
			
			const elementTypeOptions = [
				{ label: 'Paragraph', value: 'p' },
				{ label: 'Span', value: 'span' },
				{ label: 'Div', value: 'div' },
				{ label: 'H1', value: 'h1' },
				{ label: 'H2', value: 'h2' },
				{ label: 'H3', value: 'h3' },
				{ label: 'H4', value: 'h4' },
				{ label: 'H5', value: 'h5' },
				{ label: 'H6', value: 'h6' },
			];
			
			const fontSizeOptions = [
				{ label: 'Default', value: '' },
				{ label: 'Tiny', value: 'font-tiny' },
				{ label: 'Small', value: 'font-small' },
				{ label: 'Regular', value: 'font-medium' },
				{ label: 'Large', value: 'font-large' },
				{ label: 'XLarge', value: 'font-xl' },
				{ label: 'XXLarge', value: 'font-xxl' },
				{ label: 'XXLarge', value: 'font-xxl' },
		
				{ label: '4px', value: 'font-4px'},
				{ label: '8px', value: 'font-8px'},
				{ label: '10px', value: 'font-10px'}, 
				{ label: '12px', value: 'font-12px'}, 
				{ label: '14px', value: 'font-14px'}, 
				{ label: '16px', value: 'font-16px'}, 
				{ label: '18px', value: 'font-18px'}, 
				{ label: '20px', value: 'font-20px'}, 
				{ label: '22px', value: 'font-22px'}, 
				{ label: '24px', value: 'font-24px'}, 
				{ label: '32px', value: 'font-32px'}, 
				{ label: '36px', value: 'font-36px'}, 
				{ label: '48px', value: 'font-48px'}, 
				{ label: '56px', value: 'font-56px'}, 
				{ label: '64px', value: 'font-64px'}, 
				{ label: '72px', value: 'font-72px'}
			];
			
			const letterSpacingOptions = [
				{ label: 'Default', value: '' },
				{ label: '1px', value: 'space-1px'},
				{ label: '2px', value: 'space-2px'},
				{ label: '3px', value: 'space-3px'}, 
				{ label: '4px', value: 'space-4px'}, 
				{ label: '5px', value: 'space-5px'}, 
				{ label: '6px', value: 'space-6px'}, 
				{ label: '7px', value: 'space-7px'}, 
				{ label: '8px', value: 'space-8px'}, 
				{ label: '9px', value: 'space-9px'}, 
				{ label: '10px', value: 'space-10px'}, 
			];
			
			const fontWeightOptions = [
				{ label: 'Default', value: '' },
				{ label: 'Thin', value: 'thin-100'},
				{ label: 'Extra Light', value: 'extra-light-200'},
				{ label: 'Light', value: 'light-300'},
				{ label: 'Regular', value: 'regular-400'},
				{ label: 'Medium', value: 'medium-500'}, 
				{ label: 'Semi Bold', value: 'bold-600'},
				{ label: 'Bold', value: 'bold-700'},
				{ label: 'Extra Bold', value: 'extra-bold-800'}, 
				{ label: 'Black', value: 'black-900'},
			];

			const lineHeightOptions = [
				{ label: 'Default', value: '' },
				{ label: 'Half', value: 'lineheight-half'},
				{ label: '1', value: 'lineheight-1'},
				{ label: '1.5', value: 'lineheight-1-5'},
				{ label: 'Double', value: 'lineheight-double'}
			];
			
			props.setAttributes( { className: `${props.attributes.font} ${props.attributes.alignment} ${props.attributes.textColorClass} ${props.attributes.fontSize} ${props.attributes.fontWeight} ${props.attributes.textTransform} ${props.attributes.letterSpacing} ${props.attributes.lineHeight} ${props.attributes.extraCSS}` } );
			return [
				el( Fragment, {},
					el( InspectorControls, {},
						el( PanelBody, { title: 'Aller Block Controls', initialOpen: true },
							el( SelectControl, {
								label: 'Element Type',
								options : elementTypeOptions,
								onChange: ( value ) => {
									props.setAttributes( { elementType: value } );
								},
								value: props.attributes.elementType
							}),
							el( SelectControl, {
								label: 'Font',
								options : brandFontOptions,
								onChange: ( value ) => {
									props.setAttributes( { font: value } );
								},
								value: props.attributes.font
							}),
							el( SelectControl, {
								label: 'Font Weight',
								options : fontWeightOptions,
								onChange: ( value ) => {
									props.setAttributes( { fontWeight: value } );
								},
								value: props.attributes.fontWeight
							}),
							el( SelectControl, {
								label: 'Text Size',
								options : fontSizeOptions,
								onChange: ( value ) => {
									props.setAttributes( { fontSize: value } );
								},
								value: props.attributes.fontSize
							}),
							el( SelectControl, {
								label: 'Text Transform',
								options : textTransformOptions,
								onChange: ( value ) => {
									props.setAttributes( { textTransform: value } );
								},
								value: props.attributes.textTransform
							}),
							el( SelectControl, {
								label: 'Line Height',
								options : lineHeightOptions,
								onChange: ( value ) => {
									props.setAttributes( { lineHeight: value } );
								},
								value: props.attributes.lineHeight
							}),
							el( SelectControl, {
								label: 'Letter Spacing',
								options : letterSpacingOptions,
								onChange: ( value ) => {
									props.setAttributes( { letterSpacing: value } );
								},
								value: props.attributes.letterSpacing
							}),
							el( TextControl, {
								label: 'Additional CSS',
								onChange: ( value ) => {
									props.setAttributes( { extraCSS: value } );
								},
								value: props.attributes.extraCSS
							}),
							el( PanelColorSettings, {
								title: 'Text Color',
								colorSettings: [							 
									{
										colors: colorBrands,
										value: props.attributes.textColor,
										label: 'Text Color',
										onChange: ( value ) => {
											 props.setAttributes( { textColor: value } );
											 colorName = '';
											 colorObject = getColorObjectByColorValue( colorBrands, value );
											 if( colorObject ) {
												 colorName = colorObject.slug;
											 }
											 props.setAttributes( { textColorClass: colorName } );		
														  
									   },
									}
								]
							}),	
						),
					),
				),
				el(
					 BlockControls,
					 { key: 'controls' },
					  el(
						  AlignmentToolbar,
						  {
							  value: props.attributes.alignment,
							  onChange: onChangeAlignment,
						  },
					   )
				),
				el(
					RichText,
					{
						tagName: props.attributes.elementType,
						format: 'string',
						className: props.className,
						onChange: onChangeContent,
						value: props.attributes.content,
						allowedFormats: [ 'core/bold' , 'core/italic', 'core/link' ]
					}
				)
			];
		}),
		save: function( props ) {
			return (
				el( RichText.Content, {
					tagName: props.attributes.elementType,
					className: props.className,
					value: props.attributes.content
				} )
			);
		},
	} );
})(window.wp.blocks,
	window.wp.editor,
	window.wp.element,
	window.wp.components
);