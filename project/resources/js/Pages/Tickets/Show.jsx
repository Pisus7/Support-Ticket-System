import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ ticket }) {
    return (
        <AuthenticatedLayout>
            <Head title="Ticket" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8">

                    <div className="bg-white shadow rounded-lg p-6">

                        <h1 className="text-2xl font-bold mb-4">
                            {ticket.ticket_subject}
                        </h1>

                        <p className="text-gray-600 mb-4">
                            {ticket.ticket_message}
                        </p>

                        <p className="text-sm text-gray-500 mb-6">
                            Status: {ticket.ticket_status}
                        </p>

                        <Link
                            href={route('tickets.index')}
                            className="border border-gray-500 text-gray-500 px-3 py-1 rounded hover:bg-gray-500 hover:text-white transition"
                        >
                            Zurück
                        </Link>

                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
