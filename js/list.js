/**
 * Created by Kandarp on 4/24/2017.
 */
/*for saved data retrive*/
var data = null;
$(document).ready(function () {

    /* apply datatable to table */
    var table = $('#listmol').DataTable({
        stateSave: true,
        pagingType: "input"
    });

    /*add text input to each footer cell*/
    $('#listmol tfoot th').each(function () {
        var title = $(this).text();
        if (title != '') {
            $(this).html('<input type="text" size="1" />');
        }
    });

    /* append footer to header*/
    $('#listmol tfoot tr').appendTo('#listmol thead');

    /* Apply the search (to text field)*/
    table.columns([0, 1, 2, 3, 4]).every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that
                    .search(this.value)
                    .draw();
            }
        });
    });
    /*to drop downs*/
    table.columns([4, 5, 6, 7]).every(function () {
        var column = this;
        var select = $('<select><option value=""></option></select>')
            .appendTo($(column.footer()).empty())
            .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                );
                column
                    .search(val ? '^' + val + '$' : '', true, false)
                    .draw();
            });
        column.data().unique().sort().each(function (value, j) {
            select.append('<option value="' + value + '">' + value + '</option>')
        });

    });


    /* Sort function */
    function naturalCompare(a, b) {
        var ax = [], bx = [];

        a.replace(/(\d+)|(\D+)/g, function (_, $1, $2) {
            ax.push([$1 || Infinity, $2 || ""])
        });
        b.replace(/(\d+)|(\D+)/g, function (_, $1, $2) {
            bx.push([$1 || Infinity, $2 || ""])
        });

        while (ax.length && bx.length) {
            var an = ax.shift();
            var bn = bx.shift();
            var nn = (an[0] - bn[0]) || an[1].localeCompare(bn[1]);
            if (nn) return nn;
        }

        return ax.length - bx.length;
    }


    /*Restore state in column filters*/
    var state = table.state.loaded();
    if (state) {
        table.columns().eq(0).each(function (colIdx) {
            var colSearch = state.columns[colIdx].search;
            if (colSearch.search) {
                //retrive input
                $('input', table.column(colIdx).footer()).val(colSearch.search);
                //retrive select
                $('select option', table.column(colIdx).footer())
                    .each(function () {
                        var str = colSearch.search;
                        str = str.replace("^", "");
                        str = str.replace("$", "");
                        str = str.replace(/\\/g, '');
                        this.selected = (this.text == str);
                    });

            }
        });

        table.draw();
    }


    /* initially once store */
    data = table.rows({filter: 'applied'}).data();

    /*getting filtered data for save state*/
    table.on('search.dt', function () {
        //filtered rows data as arrays
        data = table.rows({filter: 'applied'}).data();
    });

    /* reload button - state refresh*/
    $("#reload").click(function () {
        /*getting filtered data for default state*/
        data = table.rows().data();
        table.state.clear();
        window.location.reload();
    });
});




