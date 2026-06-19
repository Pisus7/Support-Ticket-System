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

                        <p className="text-sm text-gray-500 mb-6">
                            Status: {ticket.ticket_status}
                        </p>

                        <div className="flex gap-3">
                            <Link
                                href={route('tickets.index')}
                                className="border border-gray-500 text-gray-500 px-3 py-1 rounded hover:bg-gray-500 hover:text-white transition"
                            >
                                Zurück
                            </Link>

                            <Link
                                href={route('tickets.edit', ticket.id)}
                                className="border border-green-500 text-green-500 px-3 py-1 rounded hover:bg-green-500 hover:text-white transition"
                            >
                                Edit
                            </Link>
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
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-sm text-gray-500 italic">
                                Bisher wurden keine Kommentare zu diesem Ticket verfasst.
                            </p>
                        )}
                    </div>

                    {isAdmin ? (
                        <div className="bg-white shadow rounded-lg p-6">
                            <h2 className="text-lg font-bold mb-4 text-gray-900">
                                Interner Admin-Kommentar
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
                    ) : (
                        <div className="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-500 italic">
                            Hinweis: Nur Administratoren können Tickets kommentieren.
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
