$(document).ready( () => {
    if (location.search.length > 0 && location.search.includes('?user_procurado=')) {
        $.ajax({
            type: 'GET',
            url: '/content_quem_seguir',
            data: `user_procurado=${location.search.replace('?user_procurado=', '')}`, //x-www-form-urlencooded
            success: data => {
                let parser = new DOMParser()
                let data_html = parser.parseFromString(data, 'text/html')
                $('#content').html(data_html.getElementById('conteudo'))
            },        
            error: erro => console.log(erro)
        })
    }



    $('#input').on('keyup', 
        e => {
                $.ajax({
                    type: 'GET',
                    url: '/content_quem_seguir',
                    data: `user_procurado=${$(e.target).val()}`, //x-www-form-urlencooded
                    success: data => {
                        let parser = new DOMParser()
                        let data_html = parser.parseFromString(data, 'text/html')
                        $('#content').html(data_html.getElementById('conteudo'))
                        if ( $(e.target).val().length === 0 ) {
                            $('#content').html('')
                        }
                    },        
                    error: erro => console.log(erro)
                })

            /*

                $.get(`app.php?date=${$(e.target).val()}`, 
                    response => {
                        $('#text').html(response)   
                    })

            */
            //metodo, url, dados, sucesso, erro
        
    })
})