import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import axios from "axios";

export default function FicheProduit({ userId }) {
    const { id } = useParams();
    const [produit, setProduit] = useState(null);
    const [celliers, setCelliers] = useState([]);
    const [cellierSelectionne, setCellierSelectionne] = useState(null);
    const [quantite, setQuantite] = useState(1); // valeur par défaut 1

    // Récupérer le produit
    useEffect(() => {
        axios.get(`http://localhost:8000/api/produits/${id}`)
            .then(res => setProduit(res.data))
            .catch(err => console.error(err));
    }, [id]);

    // Récupérer les celliers de l'utilisateur
    useEffect(() => {
        const idUtilisateur = 1; // temporaire pour tester
        axios.get(`http://localhost:8000/api/users/${idUtilisateur}/celliers`)
            .then(res => {
                const listeCelliers = Array.isArray(res.data) ? res.data : [];
                setCelliers(listeCelliers);
                if (listeCelliers.length > 0) {
                    setCellierSelectionne(listeCelliers[0].id);
                }
            })
            .catch(err => console.error(err));
    }, []);

    // Ajouter le produit au cellier sélectionné
    const ajouterProduit = () => {
        if (!produit || !cellierSelectionne) return;

        axios.post(`http://localhost:8000/api/celliers/${cellierSelectionne}/produits`, {
            produit_id: produit.id,
            quantite: quantite
        })
        .then(() => {
            alert(`Ajouté ${quantite} bouteille(s) au cellier !`);
            setQuantite(1); // reset à 1 après ajout
        })
        .catch(err => {
            console.error(err);
            alert("Erreur lors de l'ajout du produit.");
        });
    };

    if (!produit) return <p>Chargement...</p>;

    return (
        <div className="grilleBouteille">
            
                <div className="carteFicheBouteille">
                    <div className="carteColonne">
                        <img className="imageBouteille" src={produit.image} alt={produit.name} />
                        <h1><strong>Nom :</strong> {produit.name}</h1>
                        <p><strong>Catégorie :</strong> {produit.identite_produit}</p>
                        <p><strong>Millésime :</strong> {produit.millesime_produit}</p>
                        <p><strong>Origine :</strong> {produit.pays_origine}</p>
                        <p><strong>Prix :</strong> {Number(produit.price).toFixed(2)} $</p>
                    </div>
                </div>

            <div className="carteFicheBouteille">
                <div className="carteColonne">
                    <label>Choisir un cellier :</label>
                    <select
                        value={cellierSelectionne || ''}
                        onChange={e => setCellierSelectionne(e.target.value)}
                        style={{ padding: '5px' }}
                    >
                        {celliers.map(c => (
                            <option key={c.id} value={c.id}>{c.nom}</option>
                        ))}
                    </select>

                    <div style={{ display: 'flex', alignItems: 'center', gap: '5px', marginTop: '10px' }}>
                        <button onClick={() => setQuantite(q => Math.max(1, q - 1))} style={{ width: '30px' }}>-</button>
                        <input
                            type="number"
                            value={quantite}
                            onChange={e => setQuantite(Math.max(1, Math.min(99, Number(e.target.value))))}
                            style={{ width: '50px', textAlign: 'center', padding: '5px' }}
                        />
                        <button onClick={() => setQuantite(q => Math.min(99, q + 1))} style={{ width: '30px' }}>+</button>
                    </div>

                    <button 
                        onClick={ajouterProduit}
                        style={{ marginTop: '10px', padding: '8px', cursor: 'pointer' }}
                    >
                        Ajouter une bouteille
                    </button>
                </div>
            </div>
        </div>
    );
}