@extends('layouts.layout')

@section('content')
<style>
     /* Fond de page et typographie */
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background: linear-gradient(120deg, #fdfbfb, #ebedee);
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    h1 {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
        color: #2c3e50;
    }

    /* Filtres */
    .filters {
        text-align: center;
        margin-bottom: 40px;
    }

    .filters span {
        font-weight: 600;
        margin-right: 10px;
        font-size: 1rem;
    }

    .filters a {
        display: inline-block;
        margin: 5px;
        padding: 10px 18px;
        border-radius: 25px;
        border: 2px solid transparent;
        text-decoration: none;
        color: #555;
        background-color: #f5f5f5;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    .filters a.active,
    .filters a:hover {
        background-color: #880844ff;;
        color: #fff;
        border-color: #880844ff;;
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    /* Grille des vins */
    .wine-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
    }

    .wine-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .wine-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 25px rgba(0,0,0,0.15);
    }

    .wine-card img {
        max-width: 100%;
        height: 250px;
        object-fit: contain;
        display: block;
        margin: 5px auto 0px auto;
    }

    .wine-card-content {
        padding: 20px;
    }

    .wine-card-content h2 {
        font-size: 1rem;
        font-weight: 400;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .wine-card-content p {
        margin: 6px 0;
        font-size: 0.95rem;
        color: #555;
    }

    .wine-card-content p.price {
        color: #880844ff;;
        font-weight: bold;
        font-size: 1.1rem;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 50px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        margin: 5px;
        padding: 10px 16px;
        border-radius: 6px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #ffffffff;
        transition: all 0.3s ease;
        background-color: #880844ff;
    }

    .pagination span:hover {
        background-color: white;
        color: #880844ff;;
        border-color: #880844ff;;
    }

    .pagingation > span a .active {
        background-color: #880844ff;;
        color: #fff;
    }

</style>

<div class="container">
    <h1>Vins</h1>

    <!-- Filtres -->
    <div class="filters">
        <span>Filtrer par catégorie :</span>
        @foreach($categories as $catWine)
            <a href="{{ route('wines.index', ['couleur' => $catWine]) }}" 
               class="{{ $couleur == $catWine ? 'active' : '' }}">
               {{ucfirst($catWine) }}
            </a>
        @endforeach
        <a href="{{ route('wines.index') }}" class="{{ !$couleur ? 'active' : '' }}">Tout</a>
    </div>

    <!-- Grille des vins -->
    <div class="wine-grid">
        @foreach($wines as $wine)
            <div class="wine-card">
                <img src="{{ $wine->image ?? 'https://via.placeholder.com/250x270?text=Pas+d%27image' }}" 
     alt="{{ $wine->name }}">
                <div class="wine-card-content">
                    <h2>{{ $wine->name }}</h2>
                    <p class="price">{{ $wine->price }} $</p>
                    <p>Millésime: {{ $wine->millesime_produit ?? 'N/A' }}</p>
                    <p>Pays: {{ $wine->pays_origine ?? 'N/A' }}</p>
                    <p>Catégorie: Vin {{ $wine->couleur ?? 'N/A' }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $wines->links() }}
    </div>
</div>
@endsection