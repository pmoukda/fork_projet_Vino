import { useEffect, useState, useRef } from "react";
import axiosClient from "../api/axios";

export default function Filtre({ setFilter, filter }) {
	const [couleurs, setCouleurs] = useState([]);
	const [open, setOpen] = useState(false);
	const dropdownRef = useRef(null);

	useEffect(() => {
		axiosClient.get("/couleurs")
			.then(res => setCouleurs(res.data))
			.catch(err => console.error(err));
	}, []);

	// close dropdown when clicking outside
	useEffect(() => {
		const handleClickOutside = (e) => {
			if (dropdownRef.current && !dropdownRef.current.contains(e.target)) {
				setOpen(false);
			}
		};
		document.addEventListener("mousedown", handleClickOutside);
		return () => document.removeEventListener("mousedown", handleClickOutside);
	}, []);

	return (
		<form style={{ marginBottom: "1rem" }}>
			<h2 style={{ cursor: "default" }}>Filtre</h2>

			<div ref={dropdownRef} className="custom-select" onClick={() => setOpen(!open)}>
				<div className="selected">{filter || "Filtrez par couleur"}</div>
				{open && (
					<ul className="options">
						{couleurs.map(c => <li key={c} className="option" onClick={() => { setFilter(c); setOpen(false); }}>{c}</li>)}
					</ul>
				)}
			</div>

			{/*<button type="button" onClick={() => setFilter("Rouge")}>Rouge</button>
			<button type="button" onClick={() => setFilter("Blanc")}>Blanc</button>
			<button type="button" onClick={() => setFilter("Rosé")}>Rosé</button>*/}
		</form>
	);
}
