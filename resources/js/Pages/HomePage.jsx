import MainLayout from '../Layouts/Master';
import { Head } from '@inertiajs/react';

export default function HomePage() {
    return (
        <MainLayout>
            <Head title="Home" />

            <h1>Welcome to Home Page</h1>
            <p>This is the body content.</p>
        </MainLayout>
    );
}
