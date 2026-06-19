import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Index({ tickets }) {
    return (
        <AuthenticatedLayout>
            <Head title="Tickets" />

            <div className="p-6">
                <h1 className="text-3xl font-bold">
                    Tickets
                </h1>

                <p>
                    Anzahl Tickets: {tickets.length}
                </p>
            </div>
        </AuthenticatedLayout>
    );
}
