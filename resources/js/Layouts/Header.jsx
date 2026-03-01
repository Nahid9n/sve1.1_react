import { Link } from '@inertiajs/react';

export default function Header() {
    return (
        <header style={styles.header}>
            <h2>My Website</h2>
            <nav>
                <Link href="/" style={styles.link}>Home</Link>
                <Link href="/about" style={styles.link}>About</Link>
            </nav>
        </header>
    );
}

const styles = {
    header: {
        display: 'flex',
        justifyContent: 'space-between',
        padding: '15px 40px',
        background: '#111',
        color: '#fff'
    },
    link: {
        marginLeft: '15px',
        color: '#fff',
        textDecoration: 'none'
    }
};
