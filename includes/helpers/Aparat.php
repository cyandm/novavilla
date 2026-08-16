<?php

namespace Cyan\Theme\Helpers;

class Aparat
{
	private const POSTER_TRANSIENT_TTL = 12 * HOUR_IN_SECONDS;

	/**
	 * @return array{hash: string, iframe_url: string}|null
	 */
	public static function parseEmbed(string $raw): ?array
	{
		$raw = trim($raw);
		if ($raw === '') return null;

		$hash = self::extractHash($raw);
		if ($hash === null) return null;

		return ['hash' => $hash, 'iframe_url' => self::buildIframeUrl($hash)];
	}

	public static function getPosterUrl(string $hash, string $fallback_url = ''): string
	{
		$hash = sanitize_key($hash);
		if ($hash === '') return $fallback_url;

		$transient_key = 'novavilla_aparat_poster_' . $hash;
		$cached = get_transient($transient_key);
		if (is_string($cached) && $cached !== '') return $cached;

		$api_url = 'https://www.aparat.com/etc/api/video/videohash/' . rawurlencode($hash);
		$response = wp_remote_get($api_url, ['timeout' => 5, 'headers' => ['Accept' => 'application/json']]);

		if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
			$body = json_decode(wp_remote_retrieve_body($response), true);
			$poster = $body['video']['big_poster'] ?? $body['video']['small_poster'] ?? '';
			if (is_string($poster) && $poster !== '') {
				set_transient($transient_key, esc_url_raw($poster), self::POSTER_TRANSIENT_TTL);
				return esc_url_raw($poster);
			}
		}

		if ($fallback_url !== '') set_transient($transient_key, esc_url_raw($fallback_url), self::POSTER_TRANSIENT_TTL);

		return $fallback_url;
	}

	private static function extractHash(string $raw): ?string
	{
		foreach (['#/embed/([a-zA-Z0-9]+)#', '#/videohash/([a-zA-Z0-9]+)#'] as $pattern) {
			if (preg_match($pattern, $raw, $matches)) return sanitize_key($matches[1]);
		}
		return null;
	}

	private static function buildIframeUrl(string $hash): string
	{
		return sprintf('https://www.aparat.com/video/video/embed/videohash/%s/vt/frame?titleShow=true', rawurlencode($hash));
	}
}
