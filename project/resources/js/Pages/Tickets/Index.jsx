import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';

export default function Index({ tickets }) {

    const deleteTicket = (id) => {
        if (confirm('Willst du dieses Ticket wirklich löschen?')) {
            router.delete(route('tickets.destroy', id));
        }
    };

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
                            className="border border-blue-600 text-blue-600 px-4 py-2 rounded hover:bg-blue-600 hover:text-white transition"
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
                            <ul className="space-y-3">
                                {tickets.map((ticket) => (
                                    <li
                                        key={ticket.id}
                                        className="border rounded p-4 flex justify-between items-center"
                                    >
                                        <div>
                                            <p className="font-semibold">
                                                {ticket.ticket_subject}
                                            </p>

                                            <p className="text-sm text-gray-500">
                                                {ticket.ticket_status}
                                            </p>
                                        </div>

                                        <div className="flex gap-2">

                                            <Link
                                                href={route('tickets.show', ticket.id)}
                                                className="border border-blue-500 text-blue-500 px-3 py-1 rounded hover:bg-blue-500 hover:text-white transition"
                                            >
                                                View
                                            </Link>

                                            <Link
                                                href={route('tickets.edit', ticket.id)}
                                                className="border border-green-500 text-green-500 px-3 py-1 rounded hover:bg-green-500 hover:text-white transition"
                                            >
                                                Edit
                                            </Link>

                                            <button
                                                onClick={() => deleteTicket(ticket.id)}
                                                className="border border-red-500 text-red-500 px-3 py-1 rounded hover:bg-red-500 hover:text-white transition"
                                            >
                                                Delete
                                            </button>

                                        </div>
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
