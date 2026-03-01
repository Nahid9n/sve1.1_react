import Header from '../Layouts/Header';
import Footer from '../Layouts/Footer';

export default function MainLayout({ children }) {
    return (
        <>
            <Header />

            <main style={{ padding: '40px', minHeight: '80vh' }}>
                {children}
            </main>

            <Footer />
        </>
    );
}
