console.log("Defult theme: "+defaultTheme);
console.log("User theme: "+userTheme);

if (userTheme != null && userTheme != '') 
{
    if (userTheme === 'dark')
    {
        document.documentElement.classList.add('dark')
    } else
    {
        document.documentElement.classList.remove('dark')
    }

} else 
{
    if(window.matchMedia('(prefers-color-scheme: dark)').matches)
    {
        document.documentElement.classList.add('dark')
    } else
    {
        if(defaultTheme == 'dark')
        {
            document.documentElement.classList.add('dark')
        } else
        {
            document.documentElement.classList.remove('dark')
        }
    }
}