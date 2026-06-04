@extends('layouts.admin')
@section('title', 'Blog — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Gestion du Blog</h1>
    <p>{{ $articles->total() }} article{{ $articles->total() > 1 ? 's' : '' }} au total</p>
  </div>
  <div class="adm-topbar__actions">
    <a href="{{ route('admin.blog.create') }}" class="adm-btn adm-btn--yellow">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nouvel article
    </a>
  </div>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Titre</th><th>Catégorie</th><th>Auteur</th><th>Vues</th><th>Statut</th><th>Publié le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($articles as $article)
        <tr>
          <td style="max-width:260px">
            <a href="{{ route('blog.detail', $article) }}" target="_blank" style="font-weight:600;color:#042C53;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $article->titre }}</a>
          </td>
          <td>{{ $article->categorie ? '<span class="tag">'.e($article->categorie).'</span>' : '—' }}</td>
          <td style="color:#64748b">{{ $article->auteur->nom_complet }}</td>
          <td style="font-weight:600">{{ number_format($article->vues) }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($article->statut) {
              'publie'    => 'green',
              'brouillon' => 'gray',
              'archive'   => 'gray',
              default     => 'gray'
            } }}">
              {{ ucfirst($article->statut) }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $article->publie_le?->format('d/m/Y') ?? '—' }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.blog.edit', $article) }}" class="adm-btn adm-btn--outline adm-btn--sm">Modifier</a>
              <form method="POST" action="{{ route('admin.blog.destroy', $article) }}" onsubmit="return confirm('Supprimer cet article ?')">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              <h3>Aucun article</h3>
              <p>Créez votre premier article de blog.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($articles->hasPages())
    <div style="padding:16px 22px">{{ $articles->links() }}</div>
  @endif
</div>
@endsection
