import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router, usePage} from '@inertiajs/react';

export default function Index({tickets, auth}) {

    const user = auth.user;

    const deleteTicket = (id) => {
        if (confirm('Willst du dieses Ticket wirklich löschen?')) {
            router.delete(route('tickets.destroy', id));
        }
    };

    return (
        <AuthenticatedLayout>
            <Head title="Tickets"/>

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
                            Anzahl Tickets: {tickets.filter((ticket) => ticket.ticket_status !== 'archived').length}
                        </p>

                        {tickets.length === 0 ? (
                            <p className="text-gray-500">
                                Noch keine Tickets vorhanden.
                            </p>
                        ) : (
                            <ul className="space-y-3">
                                {tickets.filter((ticket) => ticket.ticket_status !== 'archived').map((ticket) => (
                                    <li
                                        key={ticket.id}
                                        className="border rounded p-4 flex justify-between items-center"
                                    >
                                        <div>
                                            <h4 style={{ marginBottom: '10px'}}className="font-bold text-gray-900 text-base tracking-tight">
                                                {ticket.ticket_subject} {ticket.admin_id === user.id && (
                                                <span
                                                    className="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 shadow-sm animate-fade-in">👤 My Ticket</span>
                                            )}
                                            </h4>



                                            <div className="flex items-center gap-2">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-inner ${
                                                    ticket.ticket_status === 'open' ? 'bg-red-50 text-red-700 border border-red-200' :
                                                        ticket.ticket_status === 'in_progress' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' :
                                                            ticket.ticket_status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                                                ticket.ticket_status === 'resolved' ? 'bg-green-50 text-green-700 border border-green-200' :
                                                                    'bg-gray-100 text-gray-700 border border-gray-300'
                                                }`}>
                                                    {ticket.ticket_status.replace('_', ' ')}
                                                </span>

                                                <span className="text-xs text-gray-400">
                                                    #{ticket.ticket_nr}
                                                </span>
                                            </div>
                                        </div>

                                        <div className="flex gap-2">
                                            <Link
                                                href={route('tickets.show', ticket.id)}
                                                className="border border-blue-500 text-blue-500 px-3 py-1 rounded hover:bg-blue-500 hover:text-white transition"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" className="w-5 h-6" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                          d="M1.5 12s3.5-6 10.5-6 10.5 6 10.5 6-3.5 6-10.5 6S1.5 12 1.5 12z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </Link>

                                            {(user.role_id === 1 || ticket.ticket_status === 'open') && (
                                                <Link href={route('tickets.edit', ticket.id)} className="border border-green-500 text-green-500 px-3 py-1 rounded hover:bg-green-500 hover:text-white transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" className="w-5 h-6" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                              d="M16.862 3.487a2.1 2.1 0 113 2.97L7.5 18.82l-4 1 1-4L16.862 3.487z"/>
                                                    </svg>
                                                </Link>
                                            )}

                                            {(user.role_id === 1 || ticket.ticket_status === 'open') && (
                                                <Link href={route('tickets.update', ticket.id)}
                                                      method="put"
                                                      data={{ ticket_status: 'archived', ticket_subject: ticket.ticket_subject, ticket_message: ticket.ticket_message, category_id: ticket.category_id}}
                                                      title="Delete Ticket (Archive)"
                                                      className="border border-red-500 text-red-500 px-3 py-1 rounded hover:bg-red-500 hover:text-white transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" className="w-5 h-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                              d="M3 6h18M8 6V4h8v2m-9 0h10m-1 0-.75 12.5A2 2 0 0 1 13.26 20H10.74a2 2 0 0 1-1.99-1.5L8 6h8"/>
                                                    </svg>
                                                </Link>
                                            )}
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
