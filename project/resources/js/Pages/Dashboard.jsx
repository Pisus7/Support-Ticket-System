import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from '@inertiajs/react';

// WICHTIG: { auth } destructured, damit React die Props richtig liest!
export default function Dashboard({auth}) {
    const isAdmin = auth.user && auth.user.role_id === 1;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Dashboard
                    </h2>

                </div>
            }
        >
            <Head title="Dashboard"/>

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                    {/* Fancy Admin-Willkommens-Banner */}
                    {isAdmin && (
                        <div
                            className="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-0.5 shadow-lg">
                            <div
                                className="rounded-[10px] bg-white p-6 dark:bg-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h3 className="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                                        Hallo, {auth.user.name}! 👋
                                    </h3>
                                    <p className="text-sm text-gray-500 mt-1">
                                        Du bist als <b>Administrator</b> eingeloggt. Du hast vollen Zugriff auf alle
                                        Tickets und Systemeinstellungen.
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Haupt-Inhalt */}
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg border border-gray-100">
                        <div className="p-8">
                            <h1 className="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">
                                Support Ticket System
                            </h1>

                            <p className="text-gray-600 mb-6 text-base">
                                Willkommen im internen IT-Support-System.
                            </p>

                            <div className="flex gap-4">
                                <Link
                                    href={route('tickets.index')}
                                    className="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    {isAdmin ? 'Alle Tickets verwalten' : 'Meine Tickets'}
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
