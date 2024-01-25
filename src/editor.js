import { createHigherOrderComponent } from '@wordpress/compose';
import {
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { FormTokenField } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

const withGutailbergControls = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        return (
            <>
                <BlockEdit key="edit" { ...props } />
                <InspectorControls>
                    <PanelBody>
					<FormTokenField
						label={__('CSS classes', 'gutailberg')}
						onChange={tokens => props.setAttributes({className: tokens.join(' ')})}
						value={(props.attributes?.className || '').split(' ').filter(Boolean)}
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
