export default function Footer() {
    return (
        <footer style={styles.footer}>
            <p>© 2026 My Website. All rights reserved.</p>
        </footer>
    );
}

const styles = {
    footer: {
        textAlign: 'center',
        padding: '20px',
        background: '#111',
        color: '#fff',
        marginTop: '40px'
    }
};
