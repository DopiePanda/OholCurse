let defaultDarkmode = "{{ env('DEFAULT_DARKMODE') }}";
let userDarkmode = "{{ auth()->user()->darkmode ?? null }}";
let defaultTheme = "{{ env('DEFAULT_THEME') }}";
let userTheme = "{{ auth()->user()->theme ?? null }}";


if (userDarkmode != null && userDarkmode != 'auto') 
{
    if (userDarkmode == 'enabled')
    {
        document.documentElement.classList.add('dark')
        console.log('User selected dark');
    } else
    {
        document.documentElement.classList.remove('dark')
        console.log('User selected light');
    }

} else 
{
    if(window.matchMedia('(prefers-color-scheme: dark)').matches)
    {
        document.documentElement.classList.add('dark')
        console.log('User system dark');
    } else
    {
        if(defaultDarkmode == 'dark')
        {
            document.documentElement.classList.add('dark')
            console.log('Default theme dark');
        } else
        {
            document.documentElement.classList.remove('dark')
            console.log('Default theme light');
        }
    }
}