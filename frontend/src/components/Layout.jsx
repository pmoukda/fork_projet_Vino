import Header from "./header";
import Footer from "./footer";

export default function Layout({ children }) {
  return (
    <div className="min-h-screen flex flex-col bg-white">
      <Header />
      <main className="flex-1 max-w-6xl mx-auto px-4 py-6 w-full">
        {children}
      </main>
      <Footer />
    </div>
  );
}