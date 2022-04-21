import React from 'react'
import { StyleSheet, Text, View } from 'react-native'
import SlidingCounter from '../components/SlidingCounter'


const SlidingCounterScreen = () => {
    return (
        <View style={styles.container}>
            <SlidingCounter />
        </View>
    )
}

export default SlidingCounterScreen

const styles = StyleSheet.create({
    container : {
        flex : 1,
        alignItems : 'center',
        justifyContent : 'center'
    }
});