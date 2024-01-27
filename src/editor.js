import { createHigherOrderComponent } from '@wordpress/compose';
import {
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { FormTokenField } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

let classList;

if ( ! classList && typeof createTailwindcssContext == 'function' && !! tailwind?.config ) {
	classList = createTailwindcssContext( tailwind.config ).getClassList();
}

const withGutailbergControls = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        return (
            <>
                <BlockEdit key="edit" { ...props } />
                <InspectorControls>
                    <PanelBody>
					<FormTokenField
						label={__('CSS classes', 'gutailberg')}
						onChange={tokens => props.setAttributes({className: tokens.sort().join(' ')})}
						value={(props.attributes?.className || '').split(' ').filter(Boolean)}
						suggestions={classList}
					/>
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
