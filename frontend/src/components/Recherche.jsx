import { useEffect, useState } from "react";
import api from "../api/axios";

export default function Recherche({ recherche, setRecherche }) {
    const [inputValue, setInputValue] = useState(recherche);

    useEffect(() => {
        if (recherche === "") {
            setInputValue("");
        }
    }, [recherche]);

    useEffect(() => {
        const timer = setTimeout(() => {
            setRecherche(inputValue);
        }, 500);

        return () => clearTimeout(timer);
    }, [inputValue, setRecherche]);

    const handleInputChange = (event) => {
        setInputValue(event.target.value);
    };

    return (
        <div className="flex items-center w-full relative">
            <input
                type="search"
                value={inputValue}
                onChange={handleInputChange}
                placeholder="Rechercher par nom ou millésime"
                className="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:ring-red-950 focus:border-red-950 text-gray-900"
            />
            <svg
                className="w-5 h-5 absolute left-3 text-gray-500"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fillRule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clipRule="evenodd"
                ></path>
            </svg>
            {inputValue && (
                <button
                    type="button"
                    onClick={() => setInputValue("")}
                    className="absolute right-3 p-1 text-gray-500 hover:text-gray-700"
                    aria-label="Clear search"
                >
                    <svg
                        className="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>
            )}
        </div>
    );
}