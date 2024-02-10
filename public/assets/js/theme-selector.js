
if (userDarkmode == 'enabled' || userDarkmode == 'disabled') 
{
    if (userDarkmode == 'enabled')
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
        if(defaultDarkmode == 'dark')
        {
            document.documentElement.classList.add('dark')
        } else
        {
            document.documentElement.classList.remove('dark')
        }
    }
}