import '@wordpress/block-editor';
import { ComponentType } from 'react';

declare module '@wordpress/block-editor' {
    export interface MediaReplaceFlowProps {
        mediaId: number;
        mediaURL: string;
        allowedTypes?: string[];
        accept?: string;
        onSelect: (media: any) => void;
        onError: (error: string) => void;
        onToggleFeaturedImage?: (value: boolean) => void;
        useFeaturedImage?: boolean;
        name: string;
    }

    export interface LinkControlProps {
        onChange: (change: any) => void;
        value?: {
            url?: string;
            opensInNewTab?: boolean;
        };
        onRemove: (any: any) => void;
        forceIsEditingLink?: any;
    }

    export interface __experimentalBlockVariationPicker {
        variations: any[];
        onSelect?: (variation: any) => void;
        selectedVariation?: any;
        label?: string;
    }

    export const MediaReplaceFlow: ComponentType<MediaReplaceFlowProps>;
    export const __experimentalLinkControl: ComponentType<LinkControlProps>;
    export const __experimentalBlockVariationPicker: ComponentType<__experimentalBlockVariationPicker>;
}
