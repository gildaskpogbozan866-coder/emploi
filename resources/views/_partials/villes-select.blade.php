@php
$villesParPays = [
    'Bénin'                       => ['Cotonou','Abomey-Calavi','Porto-Novo','Parakou','Bohicon','Natitingou','Kandi','Ouidah','Lokossa','Djougou','Abomey','Dassa-Zoumé','Savè','Savalou','Kétou','Pobè','Aplahoué','Comé','Grand-Popo','Dogbo','Glazoué','Azovè'],
    'Côte d\'Ivoire'              => ['Abidjan','Bouaké','Daloa','Yamoussoukro','Korhogo','San-Pédro','Man','Gagnoa','Divo','Abengourou','Bondoukou','Odienné'],
    'Sénégal'                     => ['Dakar','Thiès','Saint-Louis','Touba','Ziguinchor','Kaolack','Mbour','Diourbel','Tambacounda','Louga'],
    'Cameroun'                    => ['Douala','Yaoundé','Bafoussam','Garoua','Maroua','Bamenda','Ngaoundéré','Bertoua'],
    'Togo'                        => ['Lomé','Sokodé','Kara','Palimé','Atakpamé','Tsévié','Aného','Dapaong'],
    'Mali'                        => ['Bamako','Sikasso','Mopti','Gao','Ségou','Kayes','Koutiala','Tombouctou'],
    'Burkina Faso'                => ['Ouagadougou','Bobo-Dioulasso','Koudougou','Ouahigouya','Banfora','Kaya','Tenkodogo'],
    'Niger'                       => ['Niamey','Zinder','Maradi','Tahoua','Agadez','Dosso','Diffa'],
    'Guinée'                      => ['Conakry','Labé','Kankan','Kindia','Nzérékoré','Faranah'],
    'Congo'                       => ['Brazzaville','Pointe-Noire','Dolisie','Ouesso','Impfondo'],
    'République Démocratique du Congo' => ['Kinshasa','Lubumbashi','Mbuji-Mayi','Goma','Kisangani','Bukavu','Kananga'],
    'Gabon'                       => ['Libreville','Port-Gentil','Franceville','Lambaréné','Oyem'],
    'Madagascar'                  => ['Antananarivo','Toamasina','Antsirabe','Fianarantsoa','Mahajanga','Toliara'],
    'Maroc'                       => ['Casablanca','Rabat','Fès','Marrakech','Tanger','Agadir','Meknès','Oujda'],
    'Algérie'                     => ['Alger','Oran','Constantine','Annaba','Blida','Sétif','Tlemcen'],
    'Tunisie'                     => ['Tunis','Sfax','Sousse','Gabès','Bizerte','Kairouan'],
    'France'                      => ['Paris','Lyon','Marseille','Toulouse','Bordeaux','Nantes','Lille','Strasbourg','Montpellier','Rennes','Nice','Grenoble'],
    'Belgique'                    => ['Bruxelles','Liège','Anvers','Gand','Charleroi'],
];
@endphp
<select class="field__select" name="ville" required data-searchable>
  <option value="">-- Sélectionnez --</option>
  @foreach($villesParPays as $pays => $villes)
    <optgroup label="{{ $pays }}">
      @foreach($villes as $v)
        <option value="{{ $v }}" {{ ($selected ?? '') === $v ? 'selected' : '' }}>{{ $v }}</option>
      @endforeach
    </optgroup>
  @endforeach
  <optgroup label="―――――――――">
    <option value="Autre" {{ ($selected ?? '') === 'Autre' ? 'selected' : '' }}>Autre</option>
  </optgroup>
</select>
