import MainLayout from '../Layouts/Master';
import { Head } from '@inertiajs/react';

export default function About() {
    return (
        <MainLayout>
            <Head title="About" />

            <h1>About Us</h1>
            <p>This is About page content.</p>
        </MainLayout>
    );
}
