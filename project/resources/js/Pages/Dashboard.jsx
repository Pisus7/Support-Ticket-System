import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-8">
                            <h1 className="text-3xl font-bold mb-4">
                                Support Ticket System
                            </h1>

                            <p className="text-gray-600 mb-6">
                                Willkommen im internen IT-Support-System.
                            </p>

                            <Link
                                href={route('tickets.index')}
                                className="inline-block rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                            >
                                Meine Tickets
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
