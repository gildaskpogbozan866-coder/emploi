@if(session('success'))
  <div class="flash flash--success" role="alert">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="flash flash--error" role="alert">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    {{ session('error') }}
  </div>
@endif

@if(session('warning'))
  <div class="flash flash--warning" role="alert">
    {{ session('warning') }}
  </div>
@endif

@if($errors->any())
  <div class="flash flash--error" role="alert">
    <ul style="margin:0;padding-left:1.2em">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
