function setAlignmentClass( args )
{
	args.node.find( '.wpzabb-food-menu-items' )
		.removeClass( 'align-top align-left align-right align-bottom' )
		.addClass( 'align-' + args.getValue() );
}