import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Create({ categories }) {
    const { data, setData, post, processing, errors } = useForm({
        ticket_subject: '',
        ticket_message: '',
        category_id: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('tickets.store'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Neues Ticket" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8">

                    <div className="bg-white shadow-sm rounded-lg p-6">

                        <h1 className="text-3xl font-bold mb-6">
                            Neues Ticket erstellen
                        </h1>

                        <form onSubmit={submit}>

                            <div className="mb-4">
                                <label className="block mb-2 font-medium">
                                    Betreff
                                </label>

                                <input
                                    type="text"
                                    value={data.ticket_subject}
                                    onChange={(e) =>
                                        setData(
                                            'ticket_subject',
                                            e.target.value
                                        )
                                    }
                                    className="w-full border rounded p-2"
                                />

                                {errors.ticket_subject && (
                                    <p className="text-red-500 mt-1">
                                        {errors.ticket_subject}
                                    </p>
                                )}
                            </div>

                            <div className="mb-4">
                                <label className="block mb-2 font-medium">
                                    Kategorie
                                </label>

                                <select
                                    value={data.category_id}
                                    onChange={(e) =>
                                        setData(
                                            'category_id',
                                            e.target.value
                                        )
                                    }
                                    className="w-full border rounded p-2"
                                >
                                    <option value="">
                                        Bitte wählen
                                    </option>

                                    {categories.map((category) => (
                                        <option
                                            key={category.id}
                                            value={category.id}
                                        >
                                            {category.name}
                                        </option>
                                    ))}
                                </select>

                                {errors.category_id && (
                                    <p className="text-red-500 mt-1">
                                        {errors.category_id}
                                    </p>
                                )}
                            </div>

                            <div className="mb-6">
                                <label className="block mb-2 font-medium">
                                    Nachricht
                                </label>

                                <textarea
                                    rows="6"
                                    value={data.ticket_message}
                                    onChange={(e) =>
                                        setData(
                                            'ticket_message',
                                            e.target.value
                                        )
                                    }
                                    className="w-full border rounded p-2"
                                />

                                {errors.ticket_message && (
                                    <p className="text-red-500 mt-1">
                                        {errors.ticket_message}
                                    </p>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                            >
                                Ticket erstellen
                            </button>

                        </form>

                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
