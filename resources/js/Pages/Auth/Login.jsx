import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: false,
  });

  const submit = (e) => {
    e.preventDefault();
    post(route('login'), {
      onFinish: () => {
        window.location.hr
      },
    });
  };

  return (
    <>
      <Head title="Login" />

      <div className="min-h-screen flex items-center justify-center bg-black relative overflow-hidden">

        {/* Animated Gradient Background */}
        <div className="absolute inset-0
          bg-[linear-gradient(135deg,_#0085FF,_#003465)]
          bg-[length:200%_200%]
          bg-[position:100%_50%]
          animate-gradient-diagonal
          z-0" />

        {/* Overlay */}
        <div className="absolute inset-0 bg-black/50 backdrop-blur-sm z-10" />

        <div className="z-20 relative flex w-full max-w-6xl mx-auto p-6 md:flex-row flex-col items-center justify-center">

          {/* Left Logo */}
          <div className="md:w-1/2 w-full flex justify-center items-center p-6">
            <img
              src="/images/Logo.png"
              alt="Dinara Konveksi Logo"
              className="max-h-72 object-contain drop-shadow-xl animate-float animate-fade-in"
            />
          </div>

          {/* Right Form */}
          <div className="md:w-1/2 w-full p-6">
            <div className="p-8 rounded-xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-xl">

              <h2 className="text-white text-2xl font-bold mb-6">Login Dinara Konveksi</h2>

              {status && (
                <div className="mb-4 text-sm font-medium text-green-400">
                  {status}
                </div>
              )}

              <form onSubmit={submit}>
                <div className="mb-4">
                  <label htmlFor="email" className="block text-sm font-medium text-white">Email</label>
                  <input
                    id="email"
                    type="email"
                    name="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    className="mt-1 w-full px-4 py-2 rounded-md bg-white/20 border border-white/30
                      focus:outline-none focus:ring-2 focus:ring-indigo-400 text-white placeholder-gray-300"
                    autoComplete="username"
                    autoFocus
                    placeholder="you@example.com"
                  />
                  {errors.email && (
                    <p className="text-red-300 text-xs mt-1">{errors.email}</p>
                  )}
                </div>

                <div className="mb-4">
                  <label htmlFor="password" className="block text-sm font-medium text-white">Password</label>
                  <input
                    id="password"
                    type="password"
                    name="password"
                    value={data.password}
                    onChange={(e) => setData('password', e.target.value)}
                    className="mt-1 w-full px-4 py-2 rounded-md bg-white/20 border border-white/30
                      focus:outline-none focus:ring-2 focus:ring-indigo-400 text-white placeholder-gray-300"
                    autoComplete="current-password"
                    placeholder="••••••••"
                  />
                  {errors.password && (
                    <p className="text-red-300 text-xs mt-1">{errors.password}</p>
                  )}
                </div>

                <div className="mb-4 flex items-center">
                  <input
                    id="remember"
                    type="checkbox"
                    name="remember"
                    checked={data.remember}
                    onChange={(e) => setData('remember', e.target.checked)}
                    className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label htmlFor="remember" className="ml-2 block text-sm text-white">
                    Remember me
                  </label>
                </div>

                <div className="flex items-center justify-between">
                  {canResetPassword && (
                    <Link
                      href={route('password.request')}
                      className="text-sm text-indigo-300 hover:text-white underline"
                    >
                      Forgot your password?
                    </Link>
                  )}

                  <button
                    type="submit"
                    disabled={processing}
                    className="ml-4 px-5 py-2 rounded-md bg-indigo-500 hover:bg-indigo-600
                      transition-all font-semibold text-sm text-white"
                  >
                    Log in
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
