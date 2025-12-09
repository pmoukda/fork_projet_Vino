const ModaleAjouter = ({visible, messageAjout, onFermer}) => {
    if (!visible) return null;

    return (
        <section className="fixed inset-0 flex justify-center items-top">
            <div className="w-full p-6 rounded-lg shadow-lg w-80">                
                <div className="flex text-[var(--couleur-text)] justify-center gap-2">
                    {messageAjout}
                    <button
                        onClick={onFermer}
                        className="px-4 py-2 rounded  text-white"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </section>
    );
}
 export default ModaleAjouter;