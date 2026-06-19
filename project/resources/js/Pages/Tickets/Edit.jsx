import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ ticket, categories }) {

    const { data, setData, put, processing, errors } = useForm({
        ticket_subject: ticket.ticket_subject || '',
        ticket_message: ticket.ticket_message || '',
        category_id: ticket.category_id || '',
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('tickets.update', ticket.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Ticket bearbeiten" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8">

                    <div className="bg-white shadow rounded-lg p-6">

                        <h1 className="text-2xl font-bold mb-6">
                            Ticket bearbeiten
                        </h1>

                        <form onSubmit={submit} className="space-y-4">

                            <div>
                                <label className="block text-sm font-medium mb-1">
                                    Betreff
                                </label>
                                <input
                                    type="text"
                                    value={data.ticket_subject}
                                    onChange={(e) => setData('ticket_subject', e.target.value)}
                                    className="w-full border rounded px-3 py-2"
                                />
                                {errors.ticket_subject && (
                                    <p className="text-red-500 text-sm">{errors.ticket_subject}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">
                                    Nachricht
                                </label>
                                <textarea
                                    value={data.ticket_message}
                                    onChange={(e) => setData('ticket_message', e.target.value)}
                                    className="w-full border rounded px-3 py-2"
                                    rows="5"
                                />
                                {errors.ticket_message && (
                                    <p className="text-red-500 text-sm">{errors.ticket_message}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">
                                    Kategorie
                                </label>
                                <select
                                    value={data.category_id}
                                    onChange={(e) => setData('category_id', e.target.value)}
                                    className="w-full border rounded px-3 py-2"
                                >
                                    <option value="">Kategorie wählen</option>
                                    {categories.map((cat) => (
                                        <option key={cat.id} value={cat.id}>
                                            {cat.name}
                                        </option>
                                    ))}
                                </select>

                                {errors.category_id && (
                                    <p className="text-red-500 text-sm">{errors.category_id}</p>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="border border-blue-600 text-blue-600 px-4 py-2 rounded hover:bg-blue-600 hover:text-white transition"
                            >
                                Speichern
                            </button>

                        </form>

                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
