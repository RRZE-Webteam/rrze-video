// types.ts
export interface Video {
  alternative_Audio: string;
  alternative_Video_size_large: string;
  alternative_Video_size_large_height: number;
  alternative_Video_size_large_url: string;
  alternative_Video_size_large_width: number;
  alternative_Video_size_small: string;
  alternative_Video_size_small_height: string;
  alternative_Video_size_small_url: string;
  alternative_Video_size_small_width: string;
  author_name: string;
  author_url_0: string;
  description: string;
  duration: string;
  file: string;
  height: number;
  html: string;
  inLanguage: string;
  preview_image: string;
  provider_name: string;
  provider_url: string;
  provider_videoindex_url: string;
  thumbnail_height: number;
  thumbnail_url: string;
  thumbnail_width: number;
  title: string;
  transcript: string;
  transcript_de: string;
  transcript_en: string;
  type: string;
  upload_date: string;
  version: string;
  width: number;
}

export interface ApiResponse {
  video?: Video;
  oembed_api_url?: string;
  oembed_api_error?: string;
  message?: string;
  error?: string;
}

export interface OEmbedData {
  oembed_api_error: string;
  oembed_api_url: string;
  video: Video;
}
