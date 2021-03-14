import * as React from 'react';
import { useEffect, useState } from 'react';
import { Artwork as ArtworkType } from '../../services/api';
import { Image, ImageProps } from 'react-native';

export interface ArtworkProps {
  artwork: ArtworkType;
  height: number;
  width: number;
  props?: Partial<ImageProps>;
}

export function Artwork({artwork, height, width, props}: ArtworkProps) {
  const [dimensions, setDimensions] = useState<{ height: number, width: number }>();

  useEffect(() => {
    const srcHeight = artwork.height;
    const srcWidth = artwork.width;

    let targetHeight = height;
    let targetWidth = width;

    let aspectRatio = targetWidth / targetHeight;

    if (targetWidth > srcWidth) {
      targetWidth = srcWidth;
      targetHeight = targetWidth / aspectRatio;
    }

    if (targetHeight > srcHeight) {
      aspectRatio = targetWidth / targetHeight;
      targetHeight = srcHeight;
      targetWidth = targetHeight * aspectRatio;
    }

    setDimensions({
      height: targetHeight,
      width: targetWidth,
    });
  }, [artwork, height, width]);

  return (
    <>
      {dimensions && (
        <Image
          {...props}
          source={{
            uri: artwork.url,
            height: dimensions.height,
            width: dimensions.width,
            cache: 'force-cache',
          }}/>
      )}
    </>
  );
}
