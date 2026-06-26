import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, useForm} from '@inertiajs/react';

export default function Show({ ticket, auth }) {
    const isAdmin = auth.user && auth.user.role_id === 1;

    const { data, setData, post, processing, reset, errors } = useForm({
        comment_text: '',
    });

    const submitComment = (e) => {
        e.preventDefault();
        // Ersetze 'tickets.comments.store' mit deinem tatsächlichen Route-Namen
        post(route('tickets.comments.store', ticket.id), {
            onSuccess: () => reset(),
        });
    };

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

                        <p className="text-gray-600 mb-4">
                            Kategorie: {ticket.category?.name }
                        </p>

                        <p className="text-sm text-gray-500 mb-6">
                            Status: {ticket.ticket_status}
                        </p>


                        <div className="flex flex-wrap items-center gap-3 mt-4 border-t pt-4">
                            {/* Back Button */}
                            <Link
                                href={route('tickets.index')}
                                className="border border-gray-500 text-gray-500 px-3 py-1.5 rounded hover:bg-gray-500 hover:text-white transition flex items-center justify-center"
                                title="Back to List"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7 7-7M3 12h18" />
                                </svg>
                            </Link>

                            {(auth.user?.role_id === 1 || (auth.user?.role_id === 2 && ticket.ticket_status === 'open')) && ticket.ticket_status !== 'archived' && (
                                <Link
                                    href={route('tickets.edit', ticket.id)}
                                    className="border border-green-500 text-green-500 px-3 py-1.5 rounded hover:bg-green-500 hover:text-white transition flex items-center justify-center"
                                    title="Edit Ticket"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16.862 3.487a2.1 2.1 0 113 2.97L7.5 18.82l-4 1 1-4L16.862 3.487z" />
                                    </svg>
                                </Link>
                            )}

                            {/* ADMIN ONLY BUTTONS */}
                            {isAdmin && (
                                <>

                                    {/* Take Over (Only when Open) */}
                                    {ticket.ticket_status === 'open' && (
                                        <Link
                                            href={route('tickets.update', ticket.id)}
                                            method="put"
                                            data={{ ticket_status: 'in_progress', admin_id: auth.user.id, ticket_subject: ticket.ticket_subject, ticket_message: ticket.ticket_message, category_id: ticket.category_id }}
                                            title="Take over Ticket"
                                            className="inline-flex items-center gap-1.5 border border-indigo-600 text-indigo-600 px-3 py-1.5 rounded hover:bg-indigo-600 hover:text-white transition text-sm font-medium shadow-sm"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Take over</span>
                                        </Link>
                                    )}

                                    {/* Set Pending (Only when In Progress) */}
                                    {ticket.ticket_status === 'in_progress' && (
                                        <Link
                                            href={route('tickets.update', ticket.id)}
                                            method="put"
                                            data={{ ticket_status: 'pending', ticket_subject: ticket.ticket_subject, ticket_message: ticket.ticket_message, category_id: ticket.category_id }}
                                            className="border border-amber-500 text-amber-500 px-3 py-1.5 rounded hover:bg-amber-500 hover:text-white transition text-sm font-medium"
                                        >
                                            Set Pending
                                        </Link>
                                    )}

                                    {/* Hard Close (When not already closed) */}
                                    {ticket.ticket_status !== 'closed'  && ticket.ticket_status !== 'archived' && (
                                        <Link
                                            href={route('tickets.update', ticket.id)}
                                            method="put"
                                            data={{ ticket_status: 'closed', ticket_subject: ticket.ticket_subject, ticket_message: ticket.ticket_message, category_id: ticket.category_id }}
                                            className="border border-gray-600 text-gray-600 px-3 py-1.5 rounded hover:bg-gray-600 hover:text-white transition text-sm font-medium"
                                        >
                                            Close Ticket
                                        </Link>
                                    )}
                                </>
                            )}

                            {/* RESOLVED BUTTON (Außerhalb von isAdmin, damit es auch der Client sieht!) */}
                            {(ticket.ticket_status !== 'resolved' && ticket.ticket_status !== 'closed' && ticket.ticket_status !== 'archived') && (isAdmin || ticket.user_id === auth.user.id) && (
                                <Link
                                    href={route('tickets.update', ticket.id)}
                                    method="put"
                                    data={{ ticket_status: 'resolved', ticket_subject: ticket.ticket_subject, ticket_message: ticket.ticket_message, category_id: ticket.category_id }}
                                    className="border border-green-500 text-green-500 px-3 py-1.5 rounded hover:bg-green-500 hover:text-white transition text-sm font-medium ml-auto"
                                >
                                    Mark as Resolved
                                </Link>
                            )}
                        </div>

                    </div>

                    <div className="bg-white shadow rounded-lg p-6">
                        <h2 className="text-lg font-bold mb-4 text-gray-900">
                            Bisherige Kommentare ({ticket.comments?.length || 0})
                        </h2>

                        {ticket.comments && ticket.comments.length > 0 ? (
                            <div className="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                                {ticket.comments.map((comment) => (
                                    <div
                                        key={comment.id}
                                        className={`p-4 rounded-lg border ${
                                            comment.is_internal
                                                ? 'bg-yellow-50 border-yellow-200'
                                                : 'bg-gray-50 border-gray-200'
                                        }`}
                                    >
                                        <div className="flex justify-between items-center mb-2 text-xs text-gray-500">
                                            <span className="font-semibold text-gray-700">
                                                {comment.user?.role_id === 1 ? '(ADMIN) ': ''}
                                                {comment.user?.name || 'Unbekannter Nutzer'}
                                                {comment.is_internal && ' (Intern)'}
                                            </span>
                                            <span>
                                                {new Date(comment.created_at).toLocaleString('de-DE', {
                                                    dateStyle: 'short',
                                                    timeStyle: 'short'
                                                })}
                                            </span>
                                        </div>
                                        <p className="text-gray-800 text-sm whitespace-pre-line">
                                            {comment.content}
                                        </p>
                                        {((comment.user?.id === auth.user.id) || (auth.user?.role_id === 1)) && (
                                            <Link
                                                href={route('comments.destroy', { ticket: ticket.id, comment: comment.id })}
                                                method="delete"
                                                className="border border-red-500 text-red-500 px-3 py-1.5 rounded hover:bg-red-500 hover:text-white transition text-sm font-medium ml-auto"
                                                style={{ marginTop: '10px'}}
                                            >
                                                Löschen
                                            </Link>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-sm text-gray-500 italic">
                                Bisher wurden keine Kommentare zu diesem Ticket verfasst.
                            </p>
                        )}
                    </div>

                        <div className="bg-white shadow rounded-lg p-6">
                            <h2 className="text-lg font-bold mb-4 text-gray-900">
                                Kommentar hinzufügen
                            </h2>

                            <form onSubmit={submitComment} className="space-y-4">
                                <div>
                                    <textarea
                                        rows="4"
                                        className="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        placeholder="Schreibe einen Kommentar zum Ticket..."
                                        value={data.comment_text}
                                        onChange={e => setData('comment_text', e.target.value)}
                                        required
                                    ></textarea>

                                    {errors.comment_text && (
                                        <div className="text-red-600 text-sm mt-1">{errors.comment_text}</div>
                                    )}
                                </div>

                                <div className="flex justify-end">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-medium transition disabled:opacity-50"
                                    >
                                        Kommentar senden
                                    </button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
