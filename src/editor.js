import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { FormTokenField } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';

let classList;

if (
	! classList &&
	typeof createTailwindcssContext == 'function' &&
	!! tailwind?.config
) {
	classList = createTailwindcssContext( tailwind.config ).getClassList();
}

// Classify classes into categories based on prefix
function classify( classes ) {
	const classified = {
		root: [],
	};
	classes.forEach( ( c ) => {
		// split the css class into prefix and rest by the last colon
		// e.g. "md:hover:text-white" => ["md:hover", "text-white"]
		const [ p, ...rest ] = c.split( /:(?!.*:)/ );
		if ( rest.length === 0 ) {
			classified.root.push( p );
			return;
		}
		if ( ! classified[ p ] ) {
			classified[ p ] = [];
		}
		classified[ p ].push( rest[ 0 ] );
	} );
	return classified;
}

function classNameToClasses( className ) {
	return classify(
		className
			? [ ...new Set( className.split( ' ' ) ) ].filter( Boolean )
			: []
	);
}

function classesToClassName( classes ) {
	return Object.entries( classes )
		.map( ( [ p, c ] ) => {
			return c.map( ( c ) => `${ p }:${ c }` ).join( ' ' );
		} )
		.join( ' ' )
		.replace( /root:/g, '' );
}

const withGutailbergControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( ! props.isSelected ) {
			return <BlockEdit { ...props } />;
		}

		const isBlockLocked = useSelect( ( select ) => {
			const { canMoveBlock, canRemoveBlock, getBlockRootClientId } =
				select( 'core/block-editor' );
			const rootClientId = getBlockRootClientId( props.clientId );

			const canMove = canMoveBlock( props.clientId, rootClientId );
			const canRemove = canRemoveBlock( props.clientId, rootClientId );
			return ! canMove || ! canRemove;
		} );

		if ( isBlockLocked ) {
			return <BlockEdit { ...props } />;
		}

		const classes = classNameToClasses( props.attributes.className );

		return (
			<>
				<BlockEdit key="edit" { ...props } />
				<InspectorControls>
					<PanelBody title="Tailwind CSS Classes">
						{ Object.entries( classes ).map(
							( [ prefix, prefixClasses ] ) => (
								<FormTokenField
									key={ prefix }
									label={ prefix }
									onChange={ ( tokens ) => {
										const newClasses = {
											...classes,
											[ prefix ]: [
												...new Set( tokens ),
											].sort(),
										};

										props.setAttributes( {
											className:
												classesToClassName(
													newClasses
												),
										} );
									} }
									value={ prefixClasses }
									suggestions={ classList }
								/>
							)
						) }
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}, 'withMyPluginControls' );

addFilter(
	'editor.BlockEdit',
	'my-plugin/with-inspector-controls',
	withGutailbergControls
);
