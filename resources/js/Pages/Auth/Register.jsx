import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <>
            <Head title="Register" />
            <div className="min-h-screen flex items-center justify-center bg-black relative overflow-hidden">

                {/* 🌈 Animated Gradient Background */}
                <div className="absolute inset-0 
                    bg-gradient-to-r from-[#0085FF] via-[#005db3] to-[#003465]
                    bg-[length:200%_200%] bg-[position:100%_50%] 
                    animate-gradient-diagonal z-0" />

                {/* Optional overlay */}
                <div className="absolute inset-0 bg-black/60 backdrop-blur-sm z-10" />

                <div className="relative z-20 w-full max-w-md p-8 rounded-xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-xl">
                    <h2 className="text-white text-2xl font-bold mb-6">Register Dinara Konveksi</h2>

                    <form onSubmit={submit}>
                        <div className="mb-4">
                            <InputLabel htmlFor="name" value="Name" className="text-white" />
                            <TextInput
                                id="name"
                                name="name"
                                value={data.name}
                                className="mt-1 block w-full"
                                autoComplete="name"
                                isFocused={true}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                            />
                            <InputError message={errors.name} className="mt-2 text-red-300" />
                        </div>

                        <div className="mb-4">
                            <InputLabel htmlFor="email" value="Email" className="text-white" />
                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />
                            <InputError message={errors.email} className="mt-2 text-red-300" />
                        </div>

                        <div className="mb-4">
                            <InputLabel htmlFor="password" value="Password" className="text-white" />
                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                                onChange={(e) => setData('password', e.target.value)}
                                required
                            />
                            <InputError message={errors.password} className="mt-2 text-red-300" />
                        </div>

                        <div className="mb-4">
                            <InputLabel htmlFor="password_confirmation" value="Confirm Password" className="text-white" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                value={data.password_confirmation}
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                                onChange={(e) =>
                                    setData('password_confirmation', e.target.value)
                                }
                                required
                            />
                            <InputError message={errors.password_confirmation} className="mt-2 text-red-300" />
                        </div>

                        <div className="mt-6 flex items-center justify-between">
                            <Link
                                href={route('login')}
                                className="text-sm text-indigo-300 hover:text-white underline"
                            >
                                Already registered?
                            </Link>

                            <PrimaryButton className="ml-4" disabled={processing}>
                                Register
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
