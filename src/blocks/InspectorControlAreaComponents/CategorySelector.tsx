// Imports for necessary WordPress libraries
import { __ } from '@wordpress/i18n';
import {
  RadioControl,
  __experimentalDivider as Divider,
  __experimentalSpacer as Spacer,
  Notice,
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element'; // eslint-disable-line import/no-unresolved
import apiFetch from '@wordpress/api-fetch';

interface Category {
  slug: string;
  name: string;
  id: number;
  parent: number;
}

interface Attributes {
  rand: string;
}

interface CategorySelectorProps {
  attributes: Attributes;
  setAttributes: (attributes: Partial<Attributes>) => void;
}

interface VideoData {
  genre: number[];
}

interface GenreData {
  slug: string;
  name: string;
  id: number;
  parent: number;
}

const CategorySelector: React.FC<CategorySelectorProps> = ({ attributes, setAttributes }) => {
  const [videoCategories, setVideoCategories] = useState<Category[]>([]);

  const handleOnChangeVideoCat = (categoryId: number | null, newValue: string) => {
    setAttributes({
      rand: newValue,
    });
  };

  const retrieveAvailableCategoryId = async (): Promise<number[]> => {
    try {
      const data = await apiFetch({ path: '/wp/v2/rrze-video/' }) as VideoData[];
      const genreIds = data.flatMap((element) => element.genre);
      const uniqueGenreIds = Array.from(new Set(genreIds));
      return uniqueGenreIds;
    } catch (error) {
      console.log(error);
      return [];
    }
  };

  const retrieveCategoryInformation = async (categoryId: number): Promise<Category> => {
    try {
      const data = await apiFetch({ path: `/wp/v2/genre/${categoryId}` }) as GenreData;
      return {
        slug: data.slug,
        name: data.name,
        id: data.id,
        parent: data.parent,
      };
    } catch (error) {
      console.log(error);
      return {} as Category;
    }
  };

  useEffect(() => {
    const fetchCategories = async () => {
      const categoryIds = await retrieveAvailableCategoryId();
      try {
        const categories = await Promise.all(categoryIds.map(retrieveCategoryInformation));
        setVideoCategories(categories);
      } catch (error) {
        console.log(error);
      }
    };
    fetchCategories();
  }, []);

  return (
    <>
      {videoCategories.length > 0 && (
        <RadioControl
          label={__('Categories', 'rrze-video')}
          options={[
            {
              label: __("Don't show random videos.", 'rrze-video'),
              value: '',
            },
            ...videoCategories.map((cat) => ({
              label: cat.name,
              value: cat.slug,
            })),
          ]}
          onChange={(value: string) => {
            if (value === '') {
              handleOnChangeVideoCat(null, '');
            } else {
              const selectedCategory = videoCategories.find((cat) => cat.slug === value);
              if (selectedCategory) {
                handleOnChangeVideoCat(selectedCategory.id, selectedCategory.slug);
              } else {
                handleOnChangeVideoCat(null, '');
              }
            }
          }}
          selected={attributes.rand}
        />
      )}
      {videoCategories.length === 0 && (
        <>
          <Divider />
          <Spacer>
            <Notice status="info" isDismissible={false}>
              {__(
                'Add Categories via Dashboard > Video Library > Categories to use this feature.',
                'rrze-video'
              )}
            </Notice>
          </Spacer>
        </>
      )}
    </>
  );
};

export default CategorySelector;
