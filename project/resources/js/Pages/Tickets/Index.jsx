import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ tickets }) {
    return (
        <AuthenticatedLayout>
            <Head title="Tickets" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">

                    <div className="flex justify-between items-center mb-6">
                        <h1 className="text-3xl font-bold">
                            Meine Tickets
                        </h1>

                        <Link
                            href={route('tickets.create')}
                            className="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            Neues Ticket
                        </Link>
                    </div>

                    <div className="bg-white shadow-sm rounded-lg p-6">
                        <p className="mb-4">
                            Anzahl Tickets: {tickets.length}
                        </p>

                        {tickets.length === 0 ? (
                            <p className="text-gray-500">
                                Noch keine Tickets vorhanden.
                            </p>
                        ) : (
                            <ul className="space-y-2">
                                {tickets.map((ticket) => (
                                    <li
                                        key={ticket.id}
                                        className="border rounded p-3"
                                    >
                                        {ticket.ticket_subject}
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
