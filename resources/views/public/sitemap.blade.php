<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($staticPages as $page)
  <url>
    <loc>{{ $page['url'] }}</loc>
    <priority>{{ $page['priority'] }}</priority>
    <changefreq>{{ $page['changefreq'] }}</changefreq>
  </url>
@endforeach
@foreach($offres as $offre)
  <url>
    <loc>{{ route('offre.detail', $offre) }}</loc>
    <lastmod>{{ $offre->updated_at->toAtomString() }}</lastmod>
    <priority>0.8</priority>
    <changefreq>weekly</changefreq>
  </url>
@endforeach
@foreach($articles as $article)
  <url>
    <loc>{{ route('blog.detail', $article->slug) }}</loc>
    <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
    <priority>0.6</priority>
    <changefreq>monthly</changefreq>
  </url>
@endforeach
</urlset>
