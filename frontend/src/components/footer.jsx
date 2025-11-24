import { Linkedin, Instagram, Github, Facebook } from "lucide-react";

export default function Footer() {
  return (
    <footer className="mt-20">

      {/* ---- SECTION PRINCIPALE ---- */}
      <div className="bg-orange-50 px-8 py-14 text-red-950 border-t border-red-200">
        
        <div className="max-w-4xl mx-auto text-center">

          {/* LOGO */}
          <h2 className="text-4xl font-serif mb-4 tracking-wide">Vino</h2>

          {/* DESCRIPTION */}
          <p className="text-sm leading-relaxed text-red-900/80 mb-8">
            Vino est votre compagnon idéal pour découvrir, gérer et savourer vos bouteilles.
            Accédez à vos celliers, explorez vos découvertes et profitez d’une expérience raffinée.
          </p>

          {/* ICÔNES SOCIALES */}
          <div className="flex justify-center gap-6 mb-10">
            <a href="#" className="transition hover:text-red-800">
              <Linkedin className="w-6 h-6" />
            </a>
            <a href="#" className="transition hover:text-red-800">
              <Github className="w-6 h-6" />
            </a>
            <a href="#" className="transition hover:text-red-800">
              <Instagram className="w-6 h-6" />
            </a>
            <a href="#" className="transition hover:text-red-800">
              <Facebook className="w-6 h-6" />
            </a>
          </div>

          {/* NAVIGATION CENTRÉE */}
          <nav>
            <ul className="flex flex-wrap justify-center gap-6 text-red-900/80 text-sm font-medium">
              <li><a href="/catalogue" className="hover:text-red-950 transition">Catalogue</a></li>
              <li><a href="/cellier" className="hover:text-red-950 transition">Mes celliers</a></li>
              <li><a href="/compte" className="hover:text-red-950 transition">Mon compte</a></li>
              <li><a href="/inscription" className="hover:text-red-950 transition">Inscription</a></li>
              <li><a href="/connexion" className="hover:text-red-950 transition">Connexion</a></li>
            </ul>
          </nav>

        </div>

      </div>

      {/* ---- BAS DE PAGE ---- */}
      <div className="bg-red-950 text-white text-center py-4 text-xs sm:text-sm tracking-wide">
        © 2025 Vino • Tous droits réservés
      </div>

    </footer>
  );
}
