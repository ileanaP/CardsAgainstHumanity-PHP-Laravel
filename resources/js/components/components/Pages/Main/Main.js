import React, { Component } from 'react';
import Button from '@material-ui/core/Button';
import Grid from '@material-ui/core/Grid';
import Box from '@material-ui/core/Box';
import Side from '../../Side/Side';
import Paper from '@material-ui/core/Paper';
import Avatar from '@material-ui/core/Avatar';
import AccountBalanceWalletRoundedIcon from '@material-ui/icons/AccountBalanceWalletRounded';
import Typography from '@material-ui/core/Typography';
import { withStyles } from '@material-ui/core/styles';
import useMediaQuery from '@material-ui/core/useMediaQuery';
import './Main.css';

const styles = {
    root: {
        color: "#ff0000",
        "&:hover": {
            color: "#0000ff"
        }
    },
    label: {
            color: 'purple',
            fontSize: 30
    },
    paddingTop: {
        paddingTop: '30px',
        fontStyle: 'italic',
        fontSize: 12
    },
    btnRoot: {
        backgroundColor: 'indigo',
        color: 'whitesmoke',
        '&:hover': {
            backgroundColor: 'lemonchiffon',
            color: 'purple'
        },
        '&:active': {
        backgroundColor: 'lemonchiffon',
        color: 'purple'
        },
        marginBottom: '24px',
        padding: '16px',
        width: 200,
        fontWeight: 'bold'
    },
    balanceBox: {
        ['@media (max-width:768px)'] : {
            margin: '0 auto'
        }
    },
    btnsBox: {
        marginTop: '64px',
        display: 'inline-block',
        ['@media (max-width:768px)'] : {
            margin: 'auto',
            marginTop: '64px'
        }
    },
    Main: {
        ['@media (max-width:768px)'] : {
            textAlign: 'center'
        }
    }
};


function Main(props) {

    const { classes } = props;

    return(
        <div className={classes.Main}>
            <Box clone pt={2} pr={1} pb={1} pl={2} width={300}
                    className={classes.balanceBox}>
                <Paper elevation={3}>
                    <Grid container spacing={2} alignItems="center" wrap="nowrap">
                        <Grid item>
                            <Box bgcolor="primary.main" clone>
                            <Avatar>
                                <AccountBalanceWalletRoundedIcon />
                            </Avatar>
                            </Box>
                        </Grid>
                        <Grid item>
                            <Typography>Balance</Typography>
                        </Grid>
                    </Grid>
                    <Grid container spacing={2} alignItems="center" wrap="nowrap">
                        <Grid item>
                            <Typography className={classes.paddingTop}>
                                (this month)
                            </Typography>
                        </Grid>
                    </Grid>
                    <Grid container justify="flex-start" spacing={1}>
                    <Grid item>
                        <Box pb={2}>
                            <Button color="primary"
                                    classes={{
                                    root: classes.root, 
                                    label: classes.label
                                    }}>
                                $746.56</Button>
                        </Box>
                    </Grid>
                    </Grid>
                </Paper>
            </Box>

            <div className={classes.btnsBox}>
                <div>
                    <Button variant="contained" className={classes.btnRoot}>
                        Add New
                    </Button>
                </div>
                <div>
                    <Button variant="contained" className={classes.btnRoot}>
                        History
                    </Button>
                </div>
                <div>
                    <Button variant="contained" className={classes.btnRoot}>
                        Savings
                    </Button>
                </div>
            </div>

            <Side />
        </div>
    );
}


export default withStyles(styles)(Main);