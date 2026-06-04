{{--
  Composant d'affichage conditionnel par permission.
  Usage : <x-can permission="manage-blog"> ... </x-can>
--}}
@props(['permission', 'role' => null])

@if($role)
  @role($role)
    {{ $slot }}
  @endrole
@elseif(auth()->check() && auth()->user()->can($permission))
  {{ $slot }}
@endif
