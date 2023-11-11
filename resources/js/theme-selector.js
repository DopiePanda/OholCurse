let defaultTheme = "{{ env('DEFAULT_THEME') }}";
let userTheme = "{{ auth()->user()->theme ?? null }}";

console.log("Defult theme: "+defaultTheme);
console.log("User theme: "+userTheme);

if (userTheme != null && userTheme != '') 
{
    if (userTheme === 'dark')
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
        if(defaultTheme == 'dark')
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